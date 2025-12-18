$(document).ready(function () {
    videoSlider();
    getNewsUpdates();
});

function videoSlider() {
    var $carousel = $("#videoCarousel");

    // Pause video when sliding to next/prev
    $carousel.on("slide.bs.carousel", function () {
        var $currentSlide = $(this).find(".carousel-item.active");

        // Pause HTML5 videos
        $currentSlide.find("video").each(function () {
            this.pause();
            this.currentTime = 0;
        });
    });

    // Detect play/pause events for videos
    $carousel.find("video").each(function () {
        var video = this;

        // When video is played -> stop auto sliding
        video.addEventListener("play", function () {
            $carousel.carousel("pause"); // stop auto slide
            $carousel.removeAttr("data-pause");
        });

        // When video is paused or ended -> resume auto sliding (5s)
        // video.addEventListener("pause", function () {
        //     $carousel.carousel({
        //         interval: 5000
        //     });
        // });
        var resumeCarousel = function () {
            $carousel.attr("data-pause", "hover");
        };

        video.addEventListener("pause", resumeCarousel);
        video.addEventListener("ended", resumeCarousel);
    });
}

// function getNewsUpdates() {
//     $.ajax({
//         url: "/get_news_update",
//         type: "GET",
//         success: function (data) {
//             $("#news_update_slides").html();

//             if (data.length > 0) {
//                 let content = "";
//                 const newsData = data.length > 1;
//                 data.forEach((newsUpdate, index) => {
//                     if(newsUpdate.news_file_type === 'mp4'){
//                         content = `
//                             <video class="embed-responsive-item rounded-top" controls>
//                                 <source src="${newsUpdate.news_file_attachment}">
//                             </video>
//                         `;
//                     } else if(["jpeg", "jpg", "png", "webp", "gif"].includes(newsUpdate.news_file_type)){
//                         content = `
//                             <img src="${newsUpdate.news_file_attachment}"
//                                 class="embed-responsive-item rounded-bottom py-1"
//                                 alt="New Update Image"
//                                 style="object-fit: contain;"
//                             />
//                         `;
//                     }

//                     let slider = newsData
//                         ? `<a class="carousel-control-prev" href="#videoCarousel" role="button" data-slide="prev">
//                                     <span class="carousel-control-prev-icon"></span>
//                                 </a>
//                                 <a class="carousel-control-next" href="#videoCarousel" role="button" data-slide="next">
//                                     <span class="carousel-control-next-icon"></span>
//                                 </a>`
//                         : "";

//                     // dati to
//                     $("#news_update_slides").append(`
//                         <div class="carousel-item ${index === 0 ? "active" : ""}">
//                             <div class="card h-100 rounded shadow-sm overflow-hidden">
//                                 <div class="card-header rounded-top bg-white">
//                                     <strong>${newsUpdate.news_title}</strong><br>
//                                     <span class="caption-text"></span>
//                                     <span class="see-more-btn text-dark" style="cursor:pointer; display:none;">
//                                         <strong>See more...</strong>
//                                     </span>
//                                 </div>
//                                 <!-- Card body with video wrapper -->
//                                 <div class="card-body p-0">
//                                     <div class="video-wrapper">
//                                         <div class="embed-responsive embed-responsive-16by9 rounded-bottom">
//                                             ${content}
//                                         </div>
//                                         ${slider}
//                                     </div>
//                                 </div>
//                             </div>
//                         </div>
//                     `);


//                     // Insert decoded HTML into the caption
//                     $("#news_update_slides .carousel-item:last .caption-text").html(`
//                         ${newsUpdate.news_caption}
//                         <span class="contact-info">
//                             For more inquiries, you may also reach us at:<br>
//                             Facebook: <a href="https://www.facebook.com/dailyoverland" class="text-primary" target="_blank">https://www.facebook.com/dailyoverland</a><br>
//                             Instagram: <a href="https://www.instagram.com/dailyoverland_/" class="text-primary" target="_blank">https://www.instagram.com/dailyoverland_/</a><br>
//                             Tiktok: <a href="https://www.tiktok.com/@dailyoverland_" class="text-primary" target="_blank">https://www.tiktok.com/@dailyoverland_</a><br>
//                             Website: <a href="https://www.dailyoverland.com/" class="text-primary" target="_blank">https://www.dailyoverland.com/</a>
//                         </span>
//                         <br>
//                     `);
//                 });
//             } else {
//                 $("#news_update_slides").append(`
//                     <div class="carousel-item active">
//                         <div class="card h-100 rounded shadow-sm overflow-hidden">
//                             <div class="card-header rounded-top text-center">
//                                 <strong>NEWS UPDATE</strong><br>
//                                 <span class="caption-text">
//                                     Stay tuned for our future News Updates and Promotions.
//                                 </span>
//                             </div>
//                             <div class="card-body p-0">
//                                 <div class="embed-responsive embed-responsive-16by9 rounded-top d-flex align-items-center justify-content-center bg-light">
//                                     <img src="images/news-update-default.gif"
//                                         class="embed-responsive-item rounded-bottom bg-default"
//                                         alt="New Update Image"
//                                         style="object-fit: contain;"
//                                     />
//                                 </div>
//                             </div>
//                         </div>
//                     </div>
//                 `);
//             }

//             // Apply truncation logic
//             $(".caption-text").each(function () {
//                 const fullHtml = $(this).html().trim(); // store full HTML
//                 const fullText = $(this).text().trim(); // plain text version
//                 const maxLength = 100; // characters before truncation

//                 if (fullText.length > maxLength) {
//                     const shortText = fullText.substring(0, maxLength) + "...";

//                     // store both versions
//                     $(this).data("full-html", fullHtml);
//                     $(this).data("short-text", shortText);

//                     // initially show truncated
//                     $(this).text(shortText);
//                     $(this).siblings(".see-more-btn").show();
//                 }
//             });

//             // Toggle expand/collapse
//             $(document)
//                 .off("click", ".see-more-btn")
//                 .on("click", ".see-more-btn", function () {
//                     const caption = $(this).siblings(".caption-text");
//                     if ($(this).text().trim() === "See more...") {
//                         caption.html(caption.data("full-html"));
//                         $(this).html("<strong>See less</strong>");
//                     } else {
//                         caption.text(caption.data("short-text"));
//                         $(this).html("<strong>See more...</strong>");
//                     }
//                 });
//         },
//         error: function (data) {
//             if (data.status !== 200) {
//                 swal(data.responseJSON.message, {
//                     icon: "error",
//                     title: "Ooops!",
//                 });
//             }
//         },
//     });
// }

function setupCarouselVideoControl() {
    const carousel = $('#videoCarousel');

    // Pause carousel when any video is playing, resume when paused
    carousel.find('video').each(function() {
        $(this).on('play', function() {
            carousel.carousel('pause'); // Pause the carousel
        });
        $(this).on('pause ended', function() {
            // Only resume if no other video is playing
            const anyPlaying = carousel.find('video').toArray().some(v => !v.paused);
            if (!anyPlaying) {
                carousel.carousel('cycle'); // Resume the carousel
            }
        });
    });
}

function getNewsUpdates() {
    $.ajax({
        url: "/get_news_update",
        type: "GET",
        success: function (data) {
            $("#news_update_slides").html("");

            if (data.length > 0) {
                data.forEach((newsUpdate, index) => {
                    let content = "";
                    if (newsUpdate.news_file_type === 'mp4') {
                        content = `
                            <video class="embed-responsive-item rounded-top" controls>
                                <source src="${newsUpdate.news_file_attachment}">
                            </video>
                        `;
                    } else if (["jpeg", "jpg", "png", "webp", "gif", "svg"].includes(newsUpdate.news_file_type)) {
                        content = `
                            <img src="${newsUpdate.news_file_attachment}"
                                class="embed-responsive-item rounded-bottom py-1"
                                alt="New Update Image"
                                style="object-fit: contain;"
                            />
                        `;
                    }

                    const multipleSlides = data.length > 1;
                    const sliderControls = multipleSlides
                        ? `<a class="carousel-control-prev" href="#videoCarousel" role="button" data-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </a>
                            <a class="carousel-control-next" href="#videoCarousel" role="button" data-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </a>`
                        : "";

                    // append each carousel item
                    $("#news_update_slides").append(`
                        <div class="carousel-item ${index === 0 ? "active" : ""}">
                            <div class="card h-100 rounded shadow-sm overflow-hidden">
                                <div class="card-header rounded-top bg-white d-flex align-items-start position-relative">
                                    <small><strong>${newsUpdate.news_title}</strong></small>
                                    <!-- Details button moved to top-right -->
                                    <div class="view-caption-btn text-primary position-absolute"
                                        style="top: 5px; right: 10px; cursor:pointer;">
                                        <small><strong>Details...</strong></small>
                                    </div>

                                    <div class="caption-data" style="display:none; margin-top:30px;">
                                        ${newsUpdate.news_caption}
                                        <br>
                                        <span class="contact-info">
                                            For more inquiries, you may also reach us at:<br>
                                            Facebook: <a href="https://www.facebook.com/dailyoverland" class="text-primary" target="_blank">https://www.facebook.com/dailyoverland</a><br>
                                            Instagram: <a href="https://www.instagram.com/dailyoverland_/" class="text-primary" target="_blank">https://www.instagram.com/dailyoverland_/</a><br>
                                            Tiktok: <a href="https://www.tiktok.com/@dailyoverland_" class="text-primary" target="_blank">https://www.tiktok.com/@dailyoverland_</a><br>
                                            Website: <a href="https://www.dailyoverland.com/" class="text-primary" target="_blank">https://www.dailyoverland.com/</a>
                                        </span>
                                    </div>

                                </div>
                                <div class="card-body p-0">
                                    <div class="video-wrapper">
                                        <div class="embed-responsive embed-responsive-16by9 rounded-bottom">
                                            ${content}
                                        </div>
                                        ${sliderControls}
                                    </div>
                                </div>
                            </div>
                        </div>
                    `);
                });
            } else {
                // No news updates
                $("#news_update_slides").append(`
                    <div class="carousel-item active">
                        <div class="card h-100 rounded shadow-sm overflow-hidden">
                            <div class="card-header rounded-top text-center">
                                <strong>NEWS UPDATE</strong>
                            </div>
                            <div class="card-body p-0">
                                <div class="embed-responsive embed-responsive-16by9 rounded-top d-flex align-items-center justify-content-center bg-light">
                                    <img src="images/news-update-default.gif"
                                        class="embed-responsive-item rounded-bottom bg-default"
                                        alt="New Update Image"
                                        style="object-fit: contain;"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                `);
            }

            setupCarouselVideoControl();

            // Click handler for View Caption
            $(document)
                .off("click", ".view-caption-btn")
                .on("click", ".view-caption-btn", function () {
                    const fullHtml = $(this).siblings(".caption-data").html();
                    $("#captionContent").html(fullHtml);

                    const newsSection = $("#news_section");
                    const screenWidth = $(window).width();

                    if(screenWidth <= 768) {
                        // Mobile: fixed popup
                        $("#captionPopup").css({
                            display: "block"
                        });
                    } else {
                        // Desktop: popup to the right of news_section
                        const offset = newsSection.offset();
                        const popupWidth = $("#captionPopup").outerWidth();
                        $("#captionPopup").css({
                            top: offset.top,
                            left: offset.left + newsSection.outerWidth() + 15, // 15px gap
                            display: "block"
                        });
                    }
                });

            // Close popup
            $(document)
                .off("click", "#closeCaption")
                .on("click", "#closeCaption", function () {
                    $("#captionPopup").hide();
                });

        },
        error: function (data) {
            if (data.status !== 200) {
                swal(data.responseJSON.message, {
                    icon: "error",
                    title: "Ooops!",
                });
            }
        },
    });
}
