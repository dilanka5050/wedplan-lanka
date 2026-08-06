<?php

namespace App\Http\Controllers;

use App\Models\GuestGroup;
use Illuminate\Http\Request;

class GuestImportController extends Controller
{
    // Column positions in the CSV (0-indexed)
    private const COL_NAME            = 0;
    private const COL_GROUP           = 1;
    private const COL_INVITATION_TYPE = 2;
    private const COL_TOTAL_INVITED   = 3;
    private const COL_PHONE           = 4;
    private const COL_EMAIL           = 5;
    private const COL_NOTES           = 6;

    private const VALID_GROUPS = ['bride side', 'groom side'];
    private const VALID_TYPES  = ['single', 'couple', 'family'];

    /**
     * Download the CSV template.
     */
    public function template()
    {
        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="wedplan-guest-import-template.csv"',
        ];

        $rows = [
            ['Name', 'Group (Bride Side / Groom Side)', 'Invitation Type (single/couple/family)', 'Total Invited (1-20)', 'Phone', 'Email', 'Notes'],
            ['Mr. & Mrs. Perera', 'Bride Side', 'couple', '2', '+94771234567', 'perera@example.com', 'Vegetarian'],
            ['Silva Family', 'Groom Side', 'family', '4', '+94779876543', '', 'Needs parking'],
            ['Nimal Bandara', 'Bride Side', 'single', '1', '', '', ''],
        ];

        $callback = function () use ($rows) {
            $handle = fopen('php://output', 'w');
            foreach ($rows as $row) {
                fputcsv($handle, $row);
            }
            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Parse and validate the uploaded CSV, store results in session, redirect to preview.
     */
    public function preview(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $groups = GuestGroup::whereIn('slug', ['bride-side', 'groom-side'])
            ->pluck('id', 'name')
            ->mapWithKeys(fn($id, $name) => [strtolower($name) => $id]);

        $valid   = [];
        $invalid = [];
        $rowNum  = 0;

        $handle = fopen($request->file('csv_file')->getRealPath(), 'r');

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;

            // Skip header row
            if ($rowNum === 1) continue;

            // Skip completely empty rows
            if (empty(array_filter($row))) continue;

            $errors = [];

            $name           = trim($row[self::COL_NAME]           ?? '');
            $groupRaw       = trim($row[self::COL_GROUP]          ?? '');
            $invitationType = strtolower(trim($row[self::COL_INVITATION_TYPE] ?? ''));
            $totalInvited   = trim($row[self::COL_TOTAL_INVITED]  ?? '');
            $phone          = trim($row[self::COL_PHONE]          ?? '');
            $email          = trim($row[self::COL_EMAIL]          ?? '');
            $notes          = trim($row[self::COL_NOTES]          ?? '');

            // Validate Name
            if (empty($name)) {
                $errors[] = 'Name is required';
            } elseif (strlen($name) > 255) {
                $errors[] = 'Name must be under 255 characters';
            }

            // Validate Group
            $groupKey    = strtolower($groupRaw);
            $groupId     = $groups[$groupKey] ?? null;
            if (!in_array($groupKey, self::VALID_GROUPS)) {
                $errors[] = "Group must be \"Bride Side\" or \"Groom Side\" (got: \"{$groupRaw}\")";
            }

            // Validate Invitation Type
            if (!in_array($invitationType, self::VALID_TYPES)) {
                $errors[] = "Invitation type must be single, couple, or family (got: \"{$invitationType}\")";
            }

            // Validate Total Invited
            if (!is_numeric($totalInvited) || (int)$totalInvited < 1 || (int)$totalInvited > 20) {
                $errors[] = 'Total invited must be a number between 1 and 20';
            }

            // Validate Email (if provided)
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors[] = "Invalid email format: \"{$email}\"";
            }

            // Validate Phone length (if provided)
            if (!empty($phone)) {
                // Normalize: strip non-digits, remove leading 0 or 94 prefix, prepend 94
                $phone = preg_replace('/\D/', '', $phone);
                if (str_starts_with($phone, '94') && strlen($phone) > 9) {
                    $phone = substr($phone, 2);
                } elseif (str_starts_with($phone, '0')) {
                    $phone = substr($phone, 1);
                }
                $phone = '94' . $phone;
                if (strlen($phone) > 20) {
                    $errors[] = 'Phone number is too long (max 20 characters)';
                }
            }

            $rowData = [
                'row'             => $rowNum,
                'name'            => $name,
                'group_raw'       => $groupRaw,
                'guest_group_id'  => $groupId,
                'invitation_type' => $invitationType,
                'total_invited'   => (int) $totalInvited,
                'phone'           => $phone ?: null,
                'email'           => $email ?: null,
                'notes'           => $notes ?: null,
            ];

            if (empty($errors)) {
                $valid[] = $rowData;
            } else {
                $rowData['errors'] = $errors;
                $invalid[] = $rowData;
            }
        }

        fclose($handle);

        if (empty($valid) && empty($invalid)) {
            return redirect()->route('guests.index')
                ->with('error', 'The CSV file appears to be empty or only contains the header row.');
        }

        // Store in session for the import step
        session(['guest_import_valid' => $valid]);

        return view('guests.import-preview', compact('valid', 'invalid'));
    }

    /**
     * Insert the validated rows from session into the database.
     */
    public function import(Request $request)
    {
        $valid = session('guest_import_valid', []);

        if (empty($valid)) {
            return redirect()->route('guests.index')
                ->with('error', 'No valid guests to import. Please upload the file again.');
        }

        $wedding = $request->user()->wedding;
        $count   = 0;

        foreach ($valid as $row) {
            $wedding->guests()->create([
                'name'            => $row['name'],
                'guest_group_id'  => $row['guest_group_id'],
                'invitation_type' => $row['invitation_type'],
                'total_invited'   => $row['total_invited'],
                'phone'           => $row['phone'],
                'email'           => $row['email'],
                'notes'           => $row['notes'],
                'rsvp_status'     => 'pending',
            ]);
            $count++;
        }

        session()->forget('guest_import_valid');

        return redirect()->route('guests.index')
            ->with('success', "{$count} guests imported successfully!");
    }
}
