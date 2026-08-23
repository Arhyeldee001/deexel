/* Quin Tech — public frontend interactions (Lenis + GSAP, with fallbacks) */
(function () {
    'use strict';

    // Signal JS is on so reveal states apply (content stays visible if JS fails)
    document.documentElement.classList.add('js');

    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* -------- Auto-tag reveal targets (no need to edit section partials) -------- */
    (function tagReveals() {
        var sections = document.querySelectorAll('main section:not(.hero)');
        sections.forEach(function (sec) {
            if (sec.querySelector('.reveal')) return;
            var wrap = sec.querySelector(':scope > .wrap');
            var blocks = wrap ? wrap.children : null;
            if (blocks && blocks.length) {
                Array.prototype.forEach.call(blocks, function (el) {
                    if (el.offsetHeight || el.children.length) el.classList.add('reveal');
                });
            } else {
                sec.classList.add('reveal');
            }
        });
    })();

    /* -------- Mobile nav toggle -------- */
    var nav = document.querySelector('.nav');
    var toggle = document.querySelector('.nav-toggle');
    if (nav && toggle) {
        toggle.addEventListener('click', function () { nav.classList.toggle('open'); });
        nav.querySelectorAll('.nav-links a').forEach(function (a) {
            a.addEventListener('click', function () { nav.classList.remove('open'); });
        });
    }

    if (reduce) {
        document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
        return;
    }

    /* -------- Stats count-up (0 → value on scroll into view) -------- */
    (function countUp() {
        var nums = document.querySelectorAll('.stats .n');
        if (!nums.length) return;

        function parse(el) {
            var raw = el.getAttribute('data-raw') || el.textContent.trim();
            el.setAttribute('data-raw', raw);
            var m = raw.match(/^(\D*?)([\d][\d.,]*)(.*)$/);
            if (!m) return null;
            var numStr = m[2];
            return {
                prefix: m[1], suffix: m[3],
                target: parseFloat(numStr.replace(/,/g, '')),
                comma: numStr.indexOf(',') > -1,
                dp: (numStr.split('.')[1] || '').length,
            };
        }
        function render(d, v) {
            var s = d.dp ? v.toFixed(d.dp) : Math.round(v).toString();
            if (d.comma) s = Number(s).toLocaleString('en-US', { minimumFractionDigits: d.dp, maximumFractionDigits: d.dp });
            return d.prefix + s + d.suffix;
        }
        function run(el) {
            var d = parse(el);
            if (!d || isNaN(d.target)) return;
            var dur = 1500, start = null;
            (function frame(ts) {
                if (!start) start = ts;
                var p = Math.min(1, (ts - start) / dur);
                el.textContent = render(d, d.target * (1 - Math.pow(1 - p, 3)));
                if (p < 1) requestAnimationFrame(frame);
                else el.textContent = render(d, d.target);
            })(performance.now());
        }

        // Zero them out up front so there's no flash from the real value
        nums.forEach(function (el) { var d = parse(el); if (d) el.textContent = render(d, 0); });

        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) { if (e.isIntersecting) { run(e.target); io.unobserve(e.target); } });
            }, { threshold: 0.4 });
            nums.forEach(function (el) { io.observe(el); });
        } else {
            nums.forEach(run);
        }
    })();

    /* -------- Lenis smooth scroll -------- */
    var lenis = null;
    if (window.Lenis) {
        lenis = new Lenis({
            duration: 1.1,
            easing: function (t) { return Math.min(1, 1.001 - Math.pow(2, -10 * t)); },
            smoothWheel: true,
        });
        function raf(time) { lenis.raf(time); requestAnimationFrame(raf); }
        requestAnimationFrame(raf);

        // Anchor links → smooth scroll via Lenis
        document.querySelectorAll('a[href^="#"]').forEach(function (a) {
            a.addEventListener('click', function (e) {
                var id = a.getAttribute('href');
                if (id.length > 1) {
                    var el = document.querySelector(id);
                    if (el) { e.preventDefault(); lenis.scrollTo(el, { offset: -80 }); }
                }
            });
        });
    }

    var hasGSAP = window.gsap && window.ScrollTrigger;

    if (hasGSAP) {
        gsap.registerPlugin(ScrollTrigger);
        if (lenis) { lenis.on('scroll', ScrollTrigger.update); }

        /* Hero entrance timeline */
        var heroEls = document.querySelectorAll('.hero .pill, .hero h1, .hero .lead, .hero .hero-btns');
        if (heroEls.length) {
            gsap.set(heroEls, { opacity: 0, y: 30 });
            gsap.to(heroEls, { opacity: 1, y: 0, duration: 0.9, ease: 'power3.out', stagger: 0.12, delay: 0.08 });
        }
        var visual = document.querySelector('.hero .stage');
        if (visual) {
            gsap.set(visual, { opacity: 0, y: 40, scale: 0.98 });
            gsap.to(visual, { opacity: 1, y: 0, scale: 1, duration: 1.1, ease: 'power3.out', delay: 0.35 });
        }

        /* Scroll reveals (batched, staggered) */
        gsap.set('.reveal', { opacity: 0, y: 28 });
        ScrollTrigger.batch('.reveal', {
            start: 'top 86%',
            once: true,
            onEnter: function (batch) {
                gsap.to(batch, { opacity: 1, y: 0, duration: 0.8, ease: 'power3.out', stagger: 0.09, overwrite: true });
            },
        });

        /* Parallax: sun drifts down, hero image floats up as you scroll */
        var hero = document.querySelector('.hero');
        if (hero) {
            var sun = document.querySelector('.sun-wrap');
            if (sun) {
                gsap.to(sun, { yPercent: 22, ease: 'none',
                    scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
            }
            var hv = document.querySelector('.hero-visual');
            if (hv) {
                gsap.to(hv, { yPercent: -8, ease: 'none',
                    scrollTrigger: { trigger: hero, start: 'top top', end: 'bottom top', scrub: true } });
            }
        }

        ScrollTrigger.refresh();
    } else {
        /* Fallback: IntersectionObserver reveals */
        if ('IntersectionObserver' in window) {
            var io = new IntersectionObserver(function (entries) {
                entries.forEach(function (e) {
                    if (e.isIntersecting) { e.target.classList.add('in'); io.unobserve(e.target); }
                });
            }, { threshold: 0.12, rootMargin: '0px 0px -8% 0px' });
            document.querySelectorAll('.reveal').forEach(function (el) { io.observe(el); });
        } else {
            document.querySelectorAll('.reveal').forEach(function (el) { el.classList.add('in'); });
        }
    }
})();
