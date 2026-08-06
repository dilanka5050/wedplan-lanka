/* ============================================================
   digital5.js — Cinematic Wedding Entrance Animation
   GSAP animates SVG <g> groups clipped from couple1.png
   ============================================================ */

document.addEventListener('DOMContentLoaded', function () {
(function () {
    'use strict';

    // ── Cached DOM elements ──────────────────────────────────
    const scene        = document.getElementById('scene');
    const coupleWrap   = document.getElementById('couple-wrap');
    const entranceWrap = document.getElementById('entrance-wrap');
    const doorLeft     = document.getElementById('door-left');
    const doorRight    = document.getElementById('door-right');
    const doorLight    = document.getElementById('door-light');
    const lightRays    = document.getElementById('light-rays');
    const bloomCore    = document.getElementById('bloom-core');
    const bloomOuter   = document.getElementById('bloom-outer');
    const flash        = document.getElementById('flash');
    const mainContent  = document.getElementById('main-content');

    // Lock scroll during entrance
    document.documentElement.style.overflow = 'hidden';
    document.body.style.overflow = 'hidden';

    // ── SVG body-part groups ─────────────────────────────────
    const groomLegL  = document.getElementById('g-groom-leg-l');
    const groomLegR  = document.getElementById('g-groom-leg-r');
    const groomArmL  = document.getElementById('g-groom-arm-l');
    const groomArmR  = document.getElementById('g-groom-arm-r');
    const groomBody  = document.getElementById('g-groom-body');
    const groomHead  = document.getElementById('g-groom-head');
    const brideSkirt = document.getElementById('g-bride-skirt');
    const brideBody  = document.getElementById('g-bride-body');
    const brideArm   = document.getElementById('g-bride-arm');
    const brideVeil  = document.getElementById('g-bride-veil');
    const brideHead  = document.getElementById('g-bride-head');

    // ── Build light rays ─────────────────────────────────────
    (function buildRays() {
        for (let i = 0; i < 14; i++) {
            const ray = document.createElement('div');
            ray.className = 'ray';
            ray.style.transform = `rotate(${(360 / 14) * i}deg)`;
            ray.style.opacity   = (0.3 + Math.random() * 0.4).toFixed(2);
            lightRays.appendChild(ray);
        }
    })();

    // ── Build floating petals ────────────────────────────────
    (function buildPetals() {
        const colors = [
            'rgba(201,168,76,0.6)',
            'rgba(255,248,220,0.7)',
            'rgba(245,230,180,0.5)'
        ];
        for (let i = 0; i < 22; i++) {
            const p    = document.createElement('div');
            p.className = 'petal';
            const size = 4 + Math.random() * 8;
            p.style.cssText = `
                left:${10 + Math.random() * 80}%;
                width:${size}px; height:${size * 0.6}px;
                background:${colors[i % colors.length]};
                animation-duration:${8 + Math.random() * 10}s;
                animation-delay:${Math.random() * 12}s;
            `;
            scene.appendChild(p);
        }
    })();

    // ── Build burst particles ────────────────────────────────
    const particles = [];
    (function buildParticles() {
        const colors = ['#C9A84C', '#E8C97A', '#FFFDF5', '#F5EDD8', '#FFD54F'];
        const wrap   = document.getElementById('bloom-wrap');
        for (let i = 0; i < 60; i++) {
            const p    = document.createElement('div');
            p.className = 'particle';
            const size = 2 + Math.random() * 6;
            p.style.cssText = `
                width:${size}px; height:${size}px;
                background:${colors[i % colors.length]};
                left:50%; top:45%;
                box-shadow:0 0 ${size * 2}px ${colors[(i + 2) % colors.length]};
            `;
            wrap.appendChild(p);
            particles.push(p);
        }
    })();

    // ── Walking cycle ────────────────────────────────────────
    // GSAP animates SVG groups; svgOrigin sets the pivot in SVG-space
    let walkTL = null;

    function startWalk() {
        walkTL = gsap.timeline({ repeat: -1, yoyo: true });

        walkTL
            // Leg swing — pivot at hip (top of leg region)
            .to(groomLegL, { rotation: 16,  svgOrigin: '155 760', duration: 0.26, ease: 'sine.inOut' }, 0)
            .to(groomLegR, { rotation: -16, svgOrigin: '295 760', duration: 0.26, ease: 'sine.inOut' }, 0)

            // Arm swing — pivot at shoulder
            .to(groomArmL, { rotation: -18, svgOrigin: '65 210',  duration: 0.26, ease: 'sine.inOut' }, 0)
            .to(groomArmR, { rotation: 18,  svgOrigin: '375 210', duration: 0.26, ease: 'sine.inOut' }, 0)
            .to(brideArm,  { rotation: -14, svgOrigin: '942 210', duration: 0.26, ease: 'sine.inOut' }, 0)

            // Body bounce — subtle vertical
            .to(groomBody, { y: -4, duration: 0.13, ease: 'sine.inOut', yoyo: true, repeat: 1 }, 0)
            .to(brideBody, { y: -3, duration: 0.13, ease: 'sine.inOut', yoyo: true, repeat: 1 }, 0)

            // Head sway
            .to(groomHead, { rotation: 2,  svgOrigin: '200 0',  duration: 0.26, ease: 'sine.inOut' }, 0)
            .to(brideHead, { rotation: -2, svgOrigin: '800 0',  duration: 0.26, ease: 'sine.inOut' }, 0)

            // Dress / skirt sway
            .to(brideSkirt, { skewX: 3, svgOrigin: '756 660', duration: 0.26, ease: 'sine.inOut' }, 0)

            // Veil flutter
            .to(brideVeil, { skewX: 4, svgOrigin: '1023 0', duration: 0.3, ease: 'sine.inOut' }, 0);
    }

    function stopWalk() {
        if (!walkTL) return;
        walkTL.kill();
        walkTL = null;
        gsap.to([groomLegL, groomLegR, groomArmL, groomArmR, brideArm], {
            rotation: 0, duration: 0.35, ease: 'power2.out'
        });
        gsap.to([groomBody, brideBody], { y: 0, duration: 0.2 });
        gsap.to([groomHead, brideHead], { rotation: 0, duration: 0.3 });
        gsap.to(brideSkirt, { skewX: 0, duration: 0.3 });
        gsap.to(brideVeil,  { skewX: 0, duration: 0.3 });
    }

    // ── Master timeline ──────────────────────────────────────
    const master = gsap.timeline();

    /* Stage 1 — Fade in scene + entrance zoom + couple appears */
    master
        .to(scene,        { opacity: 1, duration: 1,   ease: 'power2.inOut' }, 0)
        .from(entranceWrap, { scale: 0.92, duration: 1.2, ease: 'power2.out' }, 0)
        .to(coupleWrap,   { opacity: 1, duration: 0.8, ease: 'power2.inOut' }, 0.4);

    /* Stage 2 — Couple walks toward door: scale 0.18 → 1.0, translate up */
    master.add(() => startWalk(), 1.2);

    master.to(coupleWrap, {
        scale: 1.0,
        y: () => -(window.innerHeight * 0.30),
        duration: 4.5,
        ease: 'power1.inOut',
    }, 1.2);

    /* Stage 3 — Arrive at door: stop walk, bride turns, groom raises arm */
    master.add(() => stopWalk(), 5.7);

    // Bride turns slightly toward groom
    master.to(brideBody, {
        rotation: -6, svgOrigin: '790 195', duration: 0.5, ease: 'power2.out'
    }, 5.9);
    master.to(brideHead, {
        rotation: -8, svgOrigin: '800 0', duration: 0.5, ease: 'power2.out'
    }, 5.9);

    // Groom raises right arm toward door handle
    master.to(groomArmR, {
        rotation: -65, svgOrigin: '375 210', duration: 0.6, ease: 'power2.out'
    }, 5.9);

    // Door knob rotate
    master.to('#door-left .door-knob, #door-right .door-knob', {
        rotation: 35, duration: 0.4, ease: 'power2.inOut'
    }, 6.5);

    /* Stage 4 — Doors open (1.2s, power3.inOut) */
    master.to(doorLeft,  { rotateY: -75, duration: 1.2, ease: 'power3.inOut' }, 6.8);
    master.to(doorRight, { rotateY:  75, duration: 1.2, ease: 'power3.inOut' }, 6.8);

    /* Stage 5 — Light reveal: door-light + bloom layers */
    master.to(doorLight, { opacity: 1, scale: 1,   duration: 0.5, ease: 'power2.in'  }, 6.9);
    master.to(doorLight, { scale: 1.5,              duration: 0.9, ease: 'power3.out' }, 7.4);

    master.to(bloomCore, {
        opacity: 1, scale: 1,
        duration: 0.5, ease: 'power2.in',
        onStart: () => gsap.set(bloomCore, { xPercent: -50, yPercent: -50 })
    }, 7.0);
    master.to(bloomCore,  { scale: 2.8, duration: 1.0, ease: 'power3.out' }, 7.5);

    master.to(bloomOuter, {
        opacity: 1, scale: 1,
        duration: 0.8, ease: 'power2.out',
        onStart: () => gsap.set(bloomOuter, { xPercent: -50, yPercent: -50 })
    }, 7.2);
    master.to(bloomOuter, { scale: 2.0, duration: 1.2, ease: 'power2.out' }, 7.8);

    /* Stage 6 — Light rays */
    master.to(lightRays, { opacity: 0.7, duration: 0.6, ease: 'power2.out' }, 7.1);
    master.to(lightRays, { rotation: 30, duration: 8, ease: 'none', repeat: -1 }, 7.1);

    /* Stage 7 — Particles burst */
    master.add(() => launchParticles(), 7.3);

    /* Stage 8 — Camera push */
    master.to(scene, { scale: 1.25, duration: 1.0, ease: 'power2.inOut' }, 7.8);

    /* Stage 9 — Flash */
    master.to(flash, {
        opacity: 1, scale: 3,
        duration: 0.7, ease: 'power3.in',
        onStart: () => { flash.style.pointerEvents = 'all'; }
    }, 8.6);

    /* Stage 10 — Hold 300ms → reveal main content */
    master.add(() => {
        setTimeout(() => {
            // Hide entrance scene, show scrollable content
            document.getElementById('scene').style.display = 'none';
            mainContent.classList.add('visible');
            document.documentElement.style.overflow = '';
            document.body.style.overflow = '';
            gsap.to(flash, { opacity: 0, duration: 0.8, ease: 'power2.inOut' });
            initScrollReveal();
        }, 300);
    }, 9.4);

    // ── Particle burst ───────────────────────────────────────
    function launchParticles() {
        particles.forEach((p) => {
            const angle = Math.random() * Math.PI * 2;
            const dist  = 80 + Math.random() * 220;
            const dur   = 1.2 + Math.random() * 1.8;
            gsap.fromTo(p,
                { opacity: 0, x: 0, y: 0, scale: 0, rotation: 0 },
                {
                    opacity: 0.9,
                    x: Math.cos(angle) * dist,
                    y: Math.sin(angle) * dist - 60,
                    scale: 0.5 + Math.random(),
                    rotation: Math.random() * 360,
                    duration: dur * 0.4,
                    delay: Math.random() * 0.6,
                    ease: 'power2.out',
                    onComplete() {
                        gsap.to(p, {
                            opacity: 0,
                            y: `-=${40 + Math.random() * 80}`,
                            scale: 0,
                            duration: dur * 0.6,
                            ease: 'power1.in',
                        });
                    }
                }
            );
        });
    }

    // ── Scroll reveal observer ───────────────────────────────────────
    function initScrollReveal() {
        const els = document.querySelectorAll('.reveal');
        const io  = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 80);
                    io.unobserve(entry.target);
                }
            });
        }, { threshold: 0.12 });
        els.forEach(el => io.observe(el));
    }

    // ── Music ────────────────────────────────────────────────
    const audio    = document.getElementById('bg-music');
    const musicBtn = document.getElementById('music-btn');
    let musicOn    = false;

    window.toggleMusic = function () {
        if (musicOn) {
            audio.pause();
            musicBtn.textContent = '🎵 Music Off';
            musicOn = false;
        } else {
            audio.play().catch(() => {});
            musicBtn.textContent = '🎵 Music On';
            musicOn = true;
        }
    };

})();
}); // end DOMContentLoaded
