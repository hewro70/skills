/**
* Template Name: Mentor
* Template URL: https://bootstrapmade.com/mentor-free-education-bootstrap-theme/
* Updated: Aug 07 2024 with Bootstrap v5.3.3
* Author: BootstrapMade.com
* License: https://bootstrapmade.com/license/
*/

(function() {
  "use strict";

  // ===== Helpers =====
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const onReady = (fn) => {
    if (document.readyState === 'loading') {
      document.addEventListener('DOMContentLoaded', fn, { once: true });
    } else fn();
  };

  onReady(() => {
    /**
     * Apply .scrolled class to the body as the page is scrolled down
     */
    function toggleScrolled() {
      const body = $('body');
      const header = $('#header');
      if (!header) return; // لو ما في هيدر بهالصفحة
      const sticky = header.classList.contains('scroll-up-sticky') ||
                     header.classList.contains('sticky-top') ||
                     header.classList.contains('fixed-top');
      if (!sticky) return;
      if (window.scrollY > 100) body.classList.add('scrolled');
      else body.classList.remove('scrolled');
    }
    document.addEventListener('scroll', toggleScrolled);
    window.addEventListener('load', toggleScrolled);

    /**
     * Mobile nav toggle
     */
    const mobileNavToggleBtn = $('.mobile-nav-toggle');
    function mobileNavToogle() {
      document.body.classList.toggle('mobile-nav-active');
      // بدّل الأيقونة إذا الزر موجود وكان bootstrap icons مستخدم
      if (mobileNavToggleBtn) {
        mobileNavToggleBtn.classList.toggle('bi-list');
        mobileNavToggleBtn.classList.toggle('bi-x');
      }
    }
    if (mobileNavToggleBtn) {
      mobileNavToggleBtn.addEventListener('click', mobileNavToogle);
    }

    /**
     * Hide mobile nav on same-page/hash links
     */
    $$('#navmenu a').forEach(a => {
      a.addEventListener('click', () => {
        if (document.querySelector('.mobile-nav-active')) {
          mobileNavToogle();
        }
      });
    });

    /**
     * Toggle mobile nav dropdowns
     */
    $$('.navmenu .toggle-dropdown').forEach(toggler => {
      toggler.addEventListener('click', function(e) {
        e.preventDefault();
        const li = this.parentNode; // <li>
        if (!li) return;
        li.classList.toggle('active');
        const next = li.nextElementSibling;
        if (next) next.classList.toggle('dropdown-active');
        e.stopImmediatePropagation();
      });
    });

    /**
     * Preloader
     */
    const preloader = $('#preloader');
    if (preloader) {
      window.addEventListener('load', () => preloader.remove());
    }

    /**
     * Scroll top button
     */
    const scrollTopBtn = $('.scroll-top');
    function toggleScrollTop() {
      if (!scrollTopBtn) return;
      if (window.scrollY > 100) scrollTopBtn.classList.add('active');
      else scrollTopBtn.classList.remove('active');
    }
    if (scrollTopBtn) {
      scrollTopBtn.addEventListener('click', (e) => {
        e.preventDefault();
        window.scrollTo({ top: 0, behavior: 'smooth' });
      });
    }
    window.addEventListener('load', toggleScrollTop);
    document.addEventListener('scroll', toggleScrollTop);

    /**
     * Animation on scroll (AOS)
     */
    function aosInit() {
      if (window.AOS && typeof AOS.init === 'function') {
        AOS.init({ duration: 600, easing: 'ease-in-out', once: true, mirror: false });
      }
    }
    window.addEventListener('load', aosInit);

    /**
     * GLightbox (إذا محمّل)
     */
    if (window.GLightbox) {
      window.GLightbox({ selector: '.glightbox' });
    }

    /**
     * Pure Counter (إذا محمّل)
     */
    if (window.PureCounter) {
      new PureCounter();
    }

    /**
     * Init swiper sliders (إذا محمّل)
     */
    function initSwiper() {
      if (!window.Swiper) return;
      $$('.init-swiper').forEach(swiperElement => {
        const confEl = $('.swiper-config', swiperElement);
        if (!confEl) return;
        let config = {};
        try {
          config = JSON.parse(confEl.innerHTML.trim());
        } catch { /* تجاهل */ }
        if (swiperElement.classList.contains('swiper-tab') && typeof window.initSwiperWithCustomPagination === 'function') {
          window.initSwiperWithCustomPagination(swiperElement, config);
        } else {
          new Swiper(swiperElement, config);
        }
      });
    }
    window.addEventListener('load', initSwiper);
  });
})();
