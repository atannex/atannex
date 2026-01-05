/* ===============================
   SHARED UTILITIES
================================ */

const preventContextMenu = (el) => {
    el.addEventListener("contextmenu", (e) => e.preventDefault());
};

const createYouTubeIframe = (videoId, className) => {
    const iframe = document.createElement("iframe");

    iframe.className = className;
    iframe.src =
        `https://www.youtube-nocookie.com/embed/${videoId}?` +
        `autoplay=1&rel=0&modestbranding=1&controls=1&fs=0`;

    iframe.allow = "autoplay; encrypted-media";
    iframe.allowFullscreen = false;
    iframe.referrerPolicy = "strict-origin";

    return iframe;
};

/* ===============================
   LOCAL VIDEO HANDLER
================================ */

const initLocalVideos = () => {
    document.querySelectorAll("[data-lv]").forEach((wrapper) => {
        const video = wrapper.querySelector(".lv-video");
        const playBtn = wrapper.querySelector(".lv-play-btn");

        const stopPlaying = () => wrapper.classList.remove("playing");

        playBtn.addEventListener("click", () => {
            video.play();
            wrapper.classList.add("playing");
        });

        video.addEventListener("pause", stopPlaying);
        video.addEventListener("ended", stopPlaying);

        preventContextMenu(video);
    });
};

/* ===============================
   GENERIC YOUTUBE HANDLER
================================ */

const initYouTube = ({
    wrapperSelector,
    buttonSelector,
    iframeClass,
    dataAttribute,
}) => {
    document.querySelectorAll(wrapperSelector).forEach((wrapper) => {
        const playBtn = wrapper.querySelector(buttonSelector);
        const videoId = wrapper.dataset[dataAttribute];

        playBtn.addEventListener("click", () => {
            if (wrapper.classList.contains("playing")) return;

            const iframe = createYouTubeIframe(videoId, iframeClass);

            wrapper.innerHTML = "";
            wrapper.appendChild(iframe);
            wrapper.classList.add("playing");
        });

        preventContextMenu(wrapper);
    });
};

/* ===============================
   INITIALIZATION
================================ */

initLocalVideos();

initYouTube({
    wrapperSelector: "[data-yt-id]",
    buttonSelector: ".yt-play-btn",
    iframeClass: "yt-iframe",
    dataAttribute: "ytId",
});

initYouTube({
    wrapperSelector: "[data-ytg-id]",
    buttonSelector: ".ytg-play-btn",
    iframeClass: "ytg-iframe",
    dataAttribute: "ytgId",
});
