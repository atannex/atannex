(function (window, document) {
    "use strict";

    /* ==============================
   Helpers
  ============================== */
    const $ = (selector, scope = document) => scope.querySelector(selector);
    const $$ = (selector, scope = document) =>
        Array.from(scope.querySelectorAll(selector));

    const on = (el, event, handler) =>
        el && el.addEventListener(event, handler);

    const setBgFromData = (el) => {
        const bg = el?.getAttribute("data-bg");
        if (!bg) return;

        Object.assign(el.style, {
            background: `url(${bg}) center center / cover no-repeat`,
        });
    };

    /* ==============================
   Header
  ============================== */
    const header = $(".header");
    if (header) {
        const btn = $(".header__btn");
        const nav = $(".header__nav");
        const search = $(".header__search");
        const searchBtn = $(".header__search-btn");
        const searchClose = $(".header__search-close");

        const toggleMenu = () => {
            btn.classList.toggle("header__btn--active");
            nav.classList.toggle("header__nav--active");

            $(".filter--fixed")?.classList.toggle("filter--hidden");
        };

        const toggleSearch = () => {
            search.classList.toggle("header__search--active");
        };

        on(btn, "click", toggleMenu);
        on(searchBtn, "click", toggleSearch);
        on(searchClose, "click", toggleSearch);
    }

    /* ==============================
   Mobile Filter
  ============================== */
    const mfilter = $(".mfilter");
    if (mfilter) {
        on($(".filter__menu"), "click", () =>
            mfilter.classList.toggle("mfilter--active"),
        );
        on($(".mfilter__close"), "click", () =>
            mfilter.classList.toggle("mfilter--active"),
        );
    }

    /* ==============================
   Fixed Filter (Desktop)
  ============================== */
    const fixedFilter = $(".filter--fixed");
    if (fixedFilter && window.innerWidth >= 1200) {
        fixedFilter.classList.add("filter--hidden");

        window.addEventListener("scroll", () => {
            if (window.innerWidth < 1200) return;
            fixedFilter.classList.toggle(
                "filter--hidden",
                fixedFilter.getBoundingClientRect().top > 80,
            );
        });
    }

    /* ==============================
   Splide Carousels
  ============================== */
    const mountSplide = (selector, options) => {
        $$(selector).forEach((el) => new Splide(el, options).mount());
    };

    if ($(".home__carousel")) {
        new Splide(".home__carousel", {
            type: "loop",
            perPage: 5,
            gap: 30,
            speed: 800,
            arrows: false,
            pagination: false,
            breakpoints: {
                575: { perPage: 2, gap: 24 },
                767: { perPage: 3, gap: 24 },
                991: { perPage: 3, gap: 24 },
                1199: { perPage: 4, gap: 24 },
            },
        }).mount();
    }

    if ($(".hero")) {
        new Splide(".hero", {
            type: "loop",
            perPage: 1,
            pagination: true,
            arrows: false,
            speed: 1200,
        }).mount();
    }

    mountSplide(".section__carousel", {
        type: "loop",
        perPage: 6,
        gap: 24,
        arrows: false,
        pagination: false,
        breakpoints: {
            575: { perPage: 2 },
            767: { perPage: 3 },
            991: { perPage: 3 },
            1199: { perPage: 4 },
        },
    });

    mountSplide(".section__roadmap", {
        type: "loop",
        perPage: 3,
        gap: 30,
        arrows: false,
        pagination: false,
        autoHeight: true,
        breakpoints: {
            767: { perPage: 1, gap: 20 },
            991: { perPage: 2 },
        },
    });

    /* ==============================
   Backgrounds
  ============================== */
    $$(".section--bg, .section__details-bg, .hero__slide").forEach(
        setBgFromData,
    );

    /* ==============================
   SlimSelect
  ============================== */
    const slim = (selector, search = true) => {
        $(selector) &&
            new SlimSelect({
                select: selector,
                settings: { showSearch: search },
            });
    };

    ["#filter__genre", "#mfilter__genre"].forEach((id) => slim(id));

    [
        "#filter__quality",
        "#filter__rate",
        "#filter__sort",
        "#mfilter__quality",
        "#mfilter__rate",
        "#mfilter__sort",
        "#filter__season",
        "#filter__series",
        "#filter__sync",
    ].forEach((id) => slim(id, false));

    /* ==============================
   Favorites
  ============================== */
    $$(".item__favorite").forEach((el) =>
        on(el, "click", () => el.classList.toggle("item__favorite--active")),
    );

    /* ==============================
   Player
  ============================== */
    $("#player") && new Plyr("#player");

    /* ==============================
   Scrollbars
  ============================== */
    const Scrollbar = window.Scrollbar;
    [
        ".dashbox__table-wrap--1",
        ".dashbox__table-wrap--2",
        ".item__description",
    ].forEach((sel) => {
        const el = $(sel);
        el &&
            Scrollbar.init(el, {
                damping: 0.1,
                alwaysShowTracks: true,
                continuousScrolling: true,
            });
    });

    /* ==============================
   Back to top
  ============================== */
    on($(".footer__back"), "click", () =>
        window.scrollTo({ top: 0, behavior: "smooth" }),
    );
})(window, document);
