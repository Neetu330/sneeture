(function ($) {

    "use strict";

    //===== Prealoder

    $(window).on('load', function (event) {
        $('.preloader').delay(500).fadeOut(500);
    });


    //===== Mobile Menu 

    $(".navbar-toggler").on('click', function () {
        $(this).toggleClass('active');
    });

    $(".navbar-nav a").on('click', function () {
        $(".navbar-toggler").removeClass('active');
    });


    //===== close navbar-collapse when a  clicked

    $(".navbar-nav a").on('click', function () {
        $(".navbar-collapse").removeClass("show");
    });


    //===== Sticky

    $(window).on('scroll', function (event) {
        var scroll = $(window).scrollTop();
        if (scroll < 10) {
            $(".navigation").removeClass("sticky");
        } else {
            $(".navigation").addClass("sticky");
        }
    });


    //===== Section Menu Active

    var scrollLink = $('.page-scroll');
    // Active link switching
    $(window).scroll(function () {
        var scrollbarLocation = $(this).scrollTop();

        scrollLink.each(function () {

            var sectionOffset = $(this.hash).offset().top - 73;

            if (sectionOffset <= scrollbarLocation) {
                $(this).parent().addClass('active');
                $(this).parent().siblings().removeClass('active');
            }
        });
    });



    // Parallaxmouse js

    function parallaxMouse() {
        if ($('#parallax').length) {
            var scene = document.getElementById('parallax');
            var parallax = new Parallax(scene);
        };
    };
    parallaxMouse();


    //===== Progress Bar

    if ($('.progress-line').length) {
        $('.progress-line').appear(function () {
            var el = $(this);
            var percent = el.data('width');
            $(el).css('width', percent + '%');
        }, { accY: 0 });
    }


    //===== Counter Up

    $('.counter').counterUp({
        delay: 10,
        time: 1600,
    });


    //===== Magnific Popup

    $('.image-popup').magnificPopup({
        type: 'image',
        gallery: {
            enabled: true
        }
    });


    //===== Back to top

    // Show or hide the sticky footer button
    $(window).on('scroll', function (event) {
        if ($(this).scrollTop() > 600) {
            $('.back-to-top').fadeIn(200)
        } else {
            $('.back-to-top').fadeOut(200)
        }
    });


    //Animate the scroll to yop
    $('.back-to-top').on('click', function (event) {
        event.preventDefault();

        $('html, body').animate({
            scrollTop: 0,
        }, 1500);
    });



    $(document).ready(function () {
        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone;

        // 1. Define your expanded localized price tiers for all 6 packages
        const priceMap = {
            // INDIA
            "Asia/Kolkata": {
                basic: "₹25,000 - ₹45,000",
                normal: "₹50,000 - ₹85,000",
                standard: "₹90,000 - ₹1,60,000",
                silver: "₹1,50,000 - ₹3,00,000",
                gold: "₹2,50,000 - ₹5,50,000",
                platinum: "₹4,00,000 - ₹10,00,000<sup>+</sup>"
            },

            // Alias
            "Asia/Calcutta": {
                basic: "₹25,000 - ₹45,000",
                normal: "₹50,000 - ₹85,000",
                standard: "₹90,000 - ₹1,60,000",
                silver: "₹1,50,000 - ₹3,00,000",
                gold: "₹2,50,000 - ₹5,50,000",
                platinum: "₹4,00,000 - ₹10,00,000<sup>+</sup>"
            },

            // UNITED KINGDOM
            "Europe/London": {
                basic: "£500 - £900",
                normal: "£1,000 - £1,800",
                standard: "£2,000 - £3,500",
                silver: "£3,000 - £6,000",
                gold: "£5,000 - £10,000",
                platinum: "£8,000 - £20,000<sup>+</sup>"
            },

            // EUROPE
            "Europe/Paris": {
                basic: "€600 - €1,000",
                normal: "€1,200 - €2,000",
                standard: "€2,200 - €4,000",
                silver: "€3,500 - €7,000",
                gold: "€6,000 - €12,000",
                platinum: "€10,000 - €25,000<sup>+</sup>"
            },

            // UNITED STATES
            "America/New_York": {
                basic: "$700 - $1,200",
                normal: "$1,500 - $2,500",
                standard: "$3,000 - $5,000",
                silver: "$5,000 - $9,000",
                gold: "$8,000 - $15,000",
                platinum: "$15,000 - $35,000<sup>+</sup>"
            },

            // CANADA
            "America/Toronto": {
                basic: "C$900 - C$1,500",
                normal: "C$1,800 - C$3,000",
                standard: "C$3,500 - C$6,000",
                silver: "C$6,000 - C$10,000",
                gold: "C$10,000 - C$18,000",
                platinum: "C$18,000 - C$40,000<sup>+</sup>"
            },

            // AUSTRALIA
            "Australia/Sydney": {
                basic: "A$800 - A$1,400",
                normal: "A$1,600 - A$2,800",
                standard: "A$3,000 - A$5,500",
                silver: "A$5,500 - A$10,000",
                gold: "A$9,000 - A$18,000",
                platinum: "A$16,000 - A$35,000<sup>+</sup>"
            }

        };


        // GLOBAL DEFAULT PRICING
        const defaultPrices = {
            basic: "$700 - $1,200",
            normal: "$1,500 - $2,500",
            standard: "$3,000 - $5,000",
            silver: "$5,000 - $9,000",
            gold: "$8,000 - $15,000",
            platinum: "$15,000 - $35,000<sup>+</sup>"
        };
        // 3. Select the correct prices array
        const localizedPrices = priceMap[timezone] || defaultPrices;

        // 4. Update the DOM using Class Selectors (.) instead of IDs
        // Note: We use .html() instead of .text() so the <sup>+</sup> renders beautifully!
        $(".basic-price").html(localizedPrices.basic);
        $(".normal-price").html(localizedPrices.normal);
        $(".standard-price").html(localizedPrices.standard);
        $(".silver-price").html(localizedPrices.silver);
        $(".gold-price").html(localizedPrices.gold);
        $(".platinum-price").html(localizedPrices.platinum);
    });

    console.log(Intl.DateTimeFormat().resolvedOptions().timeZone);

     $('.faq-question').click(function(){

        const parent = $(this).parent();

        // Close Other Accordions
        $('.faq-item').not(parent).removeClass('active');
        $('.faq-item').not(parent).find('.faq-answer').slideUp();

        // Toggle Current Accordion
        parent.toggleClass('active');
        parent.find('.faq-answer').slideToggle();

    });

}(jQuery));