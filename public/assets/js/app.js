!function(){
    "use strict";

    // Store navbar menu HTML for twocolumn layout
    var d = document.querySelector(".navbar-menu") ? document.querySelector(".navbar-menu").innerHTML : "";
    var M = 7;
    var t = "en";
    var a = localStorage.getItem("language");

    // Function to handle sidebar collapse
    function s() {
        var e;
        document.querySelectorAll(".navbar-nav .collapse") &&
        (e = document.querySelectorAll(".navbar-nav .collapse"),
        Array.from(e).forEach(function(t) {
            var a = new bootstrap.Collapse(t, {toggle: false});
            t.addEventListener("show.bs.collapse", function(e) {
                e.stopPropagation();
                var e = t.parentElement.closest(".collapse");
                e ? (e = e.querySelectorAll(".collapse"),
                Array.from(e).forEach(function(e) {
                    e = bootstrap.Collapse.getInstance(e);
                    e !== a && e.hide();
                })) : (e = function(e) {
                    for (var t = [], a = e.parentNode.firstChild; a; )
                        1 === a.nodeType && a !== e && t.push(a),
                        a = a.nextSibling;
                    return t
                }(t.parentElement),
                Array.from(e).forEach(function(e) {
                    2 < e.childNodes.length && e.firstElementChild.setAttribute("aria-expanded", "false");
                    e = e.querySelectorAll("*[id]");
                    Array.from(e).forEach(function(e) {
                        e.classList.remove("show"),
                        2 < e.childNodes.length && (e = e.querySelectorAll("ul li a"),
                        Array.from(e).forEach(function(e) {
                            e.hasAttribute("aria-expanded") && e.setAttribute("aria-expanded", "false")
                        }))
                    })
                }))
            }),
            t.addEventListener("hide.bs.collapse", function(e) {
                e.stopPropagation();
                e = t.querySelectorAll(".collapse");
                Array.from(e).forEach(function(e) {
                    childCollapseInstance = bootstrap.Collapse.getInstance(e);
                    childCollapseInstance.hide();
                })
            })
        }))
    }

    // Handle menu item click for sidebar
    function c(e) {
        if (e.target && e.target.matches("a.nav-link span")) {
            if (0 == l(e.target.parentElement.nextElementSibling)) {
                e.target.parentElement.nextElementSibling.classList.add("dropdown-custom-right");
                e.target.parentElement.parentElement.parentElement.parentElement.classList.add("dropdown-custom-right");
                var t = e.target.parentElement.nextElementSibling;
                Array.from(t.querySelectorAll(".menu-dropdown")).forEach(function(e) {
                    e.classList.add("dropdown-custom-right")
                });
            } else if (1 == l(e.target.parentElement.nextElementSibling) && 1848 <= window.innerWidth) {
                for (var a = document.getElementsByClassName("dropdown-custom-right"); 0 < a.length; )
                    a[0].classList.remove("dropdown-custom-right");
            }
        }
        if (e.target && e.target.matches("a.nav-link")) {
            if (0 == l(e.target.nextElementSibling)) {
                e.target.nextElementSibling.classList.add("dropdown-custom-right");
                e.target.parentElement.parentElement.parentElement.classList.add("dropdown-custom-right");
                t = e.target.nextElementSibling;
                Array.from(t.querySelectorAll(".menu-dropdown")).forEach(function(e) {
                    e.classList.add("dropdown-custom-right")
                });
            } else if (1 == l(e.target.nextElementSibling) && 1848 <= window.innerWidth) {
                for (a = document.getElementsByClassName("dropdown-custom-right"); 0 < a.length; )
                    a[0].classList.remove("dropdown-custom-right");
            }
        }
    }

    // Function to toggle sidebar
    function O() {
        var e = document.documentElement.clientWidth;
        767 < e && document.querySelector(".hamburger-icon") &&
            document.querySelector(".hamburger-icon").classList.toggle("open");

        "horizontal" === document.documentElement.getAttribute("data-layout") &&
            (document.body.classList.contains("menu") ?
                document.body.classList.remove("menu") :
                document.body.classList.add("menu"));

        "vertical" === document.documentElement.getAttribute("data-layout") &&
            (e <= 1025 && 767 < e ?
                (document.body.classList.remove("vertical-sidebar-enable"),
                "sm" == document.documentElement.getAttribute("data-sidebar-size") ?
                    document.documentElement.setAttribute("data-sidebar-size", "") :
                    document.documentElement.setAttribute("data-sidebar-size", "sm")) :
                1025 < e ?
                    (document.body.classList.remove("vertical-sidebar-enable"),
                    "lg" == document.documentElement.getAttribute("data-sidebar-size") ?
                        document.documentElement.setAttribute("data-sidebar-size", "sm") :
                        document.documentElement.setAttribute("data-sidebar-size", "lg")) :
                    e <= 767 && (document.body.classList.add("vertical-sidebar-enable"),
                    document.documentElement.setAttribute("data-sidebar-size", "lg")));

        "twocolumn" == document.documentElement.getAttribute("data-layout") &&
            (document.body.classList.contains("twocolumn-panel") ?
                document.body.classList.remove("twocolumn-panel") :
                document.body.classList.add("twocolumn-panel"));
    }

    // Check if element is in viewport
    function l(e) {
        if (e) {
            var t = e.offsetTop
              , a = e.offsetLeft
              , n = e.offsetWidth
              , o = e.offsetHeight;
            if (e.offsetParent)
                for (; e.offsetParent; )
                    t += (e = e.offsetParent).offsetTop,
                    a += e.offsetLeft;
            return t >= window.pageYOffset && a >= window.pageXOffset && t + o <= window.pageYOffset + window.innerHeight && a + n <= window.pageXOffset + window.innerWidth
        }
    }

    // Initialize sidebar
    function g() {
        var e = "/" == location.pathname ? "index.html" : location.pathname.substring(1);
        (e = e.substring(e.lastIndexOf("/") + 1)) && (e = document.getElementById("navbar-nav").querySelector('[href="' + e + '"]')) &&
            (e.classList.add("active"),
            e = e.closest(".collapse.menu-dropdown")) &&
            (e.classList.add("show"),
            e.parentElement.children[0].classList.add("active"),
            e.parentElement.children[0].setAttribute("aria-expanded", "true"),
            e.parentElement.closest(".collapse.menu-dropdown")) &&
            (e.parentElement.closest(".collapse").classList.add("show"),
            e.parentElement.closest(".collapse").previousElementSibling &&
                e.parentElement.closest(".collapse").previousElementSibling.classList.add("active"),
            e.parentElement.parentElement.parentElement.parentElement.closest(".collapse.menu-dropdown")) &&
            (e.parentElement.parentElement.parentElement.parentElement.closest(".collapse").classList.add("show"),
            e.parentElement.parentElement.parentElement.parentElement.closest(".collapse").previousElementSibling) &&
            (e.parentElement.parentElement.parentElement.parentElement.closest(".collapse").previousElementSibling.classList.add("active"));
    }

    // Initialize layout
    function G() {
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementsByClassName("code-switcher");
            feather.replace();
        });

        window.addEventListener("resize", r);
        r();

        Waves.init();

        document.addEventListener("scroll", function() {
            var e;
            (e = document.getElementById("page-topbar")) &&
                (50 <= document.body.scrollTop || 50 <= document.documentElement.scrollTop ?
                    e.classList.add("topbar-shadow") :
                    e.classList.remove("topbar-shadow"));
        });

        window.addEventListener("load", function() {
            var e;
            ("twocolumn" == document.documentElement.getAttribute("data-layout") ? u : g)();

            (e = document.getElementsByClassName("vertical-overlay")) &&
                Array.from(e).forEach(function(e) {
                    e.addEventListener("click", function() {
                        document.body.classList.remove("vertical-sidebar-enable");
                        "twocolumn" == sessionStorage.getItem("data-layout") ?
                            document.body.classList.add("twocolumn-panel") :
                            document.documentElement.setAttribute("data-sidebar-size", sessionStorage.getItem("data-sidebar-size"));
                    });
                });
        });

        document.getElementById("topnav-hamburger-icon") &&
            document.getElementById("topnav-hamburger-icon").addEventListener("click", O);
    }

    // Initialize sidebar for twocolumn layout
    function u() {
        feather.replace();
        var e, t, a = "/" == location.pathname ? "index.html" : location.pathname.substring(1);
        (a = a.substring(a.lastIndexOf("/") + 1)) &&
            ("twocolumn-panel" == document.body.className &&
                document.getElementById("two-column-menu").querySelector('[href="' + a + '"]').classList.add("active"),
            (a = document.getElementById("navbar-nav").querySelector('[href="' + a + '"]')) ?
                (a.classList.add("active"),
                t = ((e = a.closest(".collapse.menu-dropdown")) &&
                    e.parentElement.closest(".collapse.menu-dropdown") ?
                    (e.classList.add("show"),
                    e.parentElement.children[0].classList.add("active"),
                    e.parentElement.closest(".collapse.menu-dropdown").parentElement.classList.add("twocolumn-item-show"),
                    e.parentElement.parentElement.parentElement.parentElement.closest(".collapse.menu-dropdown") &&
                        (t = e.parentElement.parentElement.parentElement.parentElement.closest(".collapse.menu-dropdown").getAttribute("id"),
                        e.parentElement.parentElement.parentElement.parentElement.closest(".collapse.menu-dropdown").parentElement.classList.add("twocolumn-item-show"),
                        e.parentElement.closest(".collapse.menu-dropdown").parentElement.classList.remove("twocolumn-item-show"),
                        document.getElementById("two-column-menu").querySelector('[href="#' + t + '"]')) &&
                        document.getElementById("two-column-menu").querySelector('[href="#' + t + '"]').classList.add("active"),
                    e.parentElement.closest(".collapse.menu-dropdown")) :
                    (a.closest(".collapse.menu-dropdown").parentElement.classList.add("twocolumn-item-show"),
                    e)).getAttribute("id"),
                document.getElementById("two-column-menu").querySelector('[href="#' + t + '"]') &&
                    document.getElementById("two-column-menu").querySelector('[href="#' + t + '"]').classList.add("active")) :
                document.body.classList.add("twocolumn-panel"));
    }

    // Handle window resize
    function r() {
        var e = document.documentElement.clientWidth;
        e < 1025 && 767 < e ?
            (document.body.classList.remove("twocolumn-panel"),
            "twocolumn" == sessionStorage.getItem("data-layout") &&
                (document.documentElement.setAttribute("data-layout", "twocolumn"),
                document.getElementById("customizer-layout03") &&
                    document.getElementById("customizer-layout03").click(),
                s()),
            "vertical" == sessionStorage.getItem("data-layout") &&
                document.documentElement.setAttribute("data-sidebar-size", "sm"),
            document.querySelector(".hamburger-icon") &&
                document.querySelector(".hamburger-icon").classList.add("open")) :
            1025 <= e ?
                (document.body.classList.remove("twocolumn-panel"),
                "twocolumn" == sessionStorage.getItem("data-layout") &&
                    (document.documentElement.setAttribute("data-layout", "twocolumn"),
                    document.getElementById("customizer-layout03") &&
                        document.getElementById("customizer-layout03").click(),
                    s()),
                "vertical" == sessionStorage.getItem("data-layout") &&
                    document.documentElement.setAttribute("data-sidebar-size", sessionStorage.getItem("data-sidebar-size")),
                document.querySelector(".hamburger-icon") &&
                    document.querySelector(".hamburger-icon").classList.remove("open")) :
                e <= 767 &&
                    (document.body.classList.remove("vertical-sidebar-enable"),
                    document.body.classList.add("twocolumn-panel"),
                    "twocolumn" == sessionStorage.getItem("data-layout") &&
                        (document.documentElement.setAttribute("data-layout", "vertical"),
                        s()),
                    "horizontal" != sessionStorage.getItem("data-layout") &&
                        document.documentElement.setAttribute("data-sidebar-size", "lg"),
                    document.querySelector(".hamburger-icon")) &&
                    document.querySelector(".hamburger-icon").classList.add("open");

        document.querySelectorAll("#navbar-nav > li.nav-item");
        Array.from(e).forEach(function(e) {
            e.addEventListener("click", c.bind(this), !1);
            e.addEventListener("mouseover", c.bind(this), !1);
        });
    }

    // Initialize notification handling
    function H() {
        Array.from(document.querySelectorAll("#notificationItemsTabContent .tab-pane")).forEach(function(e) {
            0 < e.querySelectorAll(".notification-item").length ?
                e.querySelector(".view-all") &&
                    (e.querySelector(".view-all").style.display = "block") :
                (e.querySelector(".view-all") &&
                    (e.querySelector(".view-all").style.display = "none"),
                e.querySelector(".empty-notification-elem") ||
                    (e.innerHTML += '<div class="empty-notification-elem">\t\t\t\t\t\t\t<div class="w-25 w-sm-50 pt-3 mx-auto">\t\t\t\t\t\t\t\t<img src="assets/images/svg/bell.svg" class="img-fluid" alt="user-pic">\t\t\t\t\t\t\t</div>\t\t\t\t\t\t\t<div class="text-center pb-5 mt-2">\t\t\t\t\t\t\t\t<h6 class="fs-18 fw-semibold lh-base">Hey! You have no any notifications </h6>\t\t\t\t\t\t\t</div>\t\t\t\t\t\t</div>'));
        });
    }

    // Handle sidebar scrollbar
    function P() {
        var e;
        "horizontal" !== document.documentElement.getAttribute("data-layout") &&
            (document.getElementById("navbar-nav") &&
                (e = new SimpleBar(document.getElementById("navbar-nav"))) &&
                e.getContentElement(),
            document.getElementsByClassName("twocolumn-iconview")[0] &&
                (e = new SimpleBar(document.getElementsByClassName("twocolumn-iconview")[0])) &&
                e.getContentElement(),
            clearTimeout(q));
    }

    // Initialize layout
    G();

    // Initialize notification handling
    document.getElementsByClassName("notification-check") &&
        (H(),
        Array.from(document.querySelectorAll(".notification-check input")).forEach(function(t) {
            t.addEventListener("change", function(e) {
                e.target.closest(".notification-item").classList.toggle("active");
                var t = document.querySelectorAll(".notification-check input:checked").length;
                e.target.closest(".notification-item").classList.contains("active");
                document.getElementById("notification-actions").style.display = 0 < t ? "block" : "none";
                document.getElementById("select-content").innerHTML = t;
            });

            document.getElementById("notificationDropdown").addEventListener("hide.bs.dropdown", function(e) {
                t.checked = false;
                document.querySelectorAll(".notification-item").forEach(function(e) {
                    e.classList.remove("active");
                });
                document.getElementById("notification-actions").style.display = "";
            });
        }));

    // Handle notification modal
    var removeNotificationModal = document.getElementById("removeNotificationModal");
    if (removeNotificationModal) {
        removeNotificationModal.addEventListener("show.bs.modal", function(e) {
            var deleteBtn = document.getElementById("delete-notification");
            if (deleteBtn) {
                deleteBtn.addEventListener("click", function() {
                    Array.from(document.querySelectorAll(".notification-item")).forEach(function(e) {
                        e.classList.contains("active") && e.remove();
                    });
                    H();
                    var closeBtn = document.getElementById("NotificationModalbtn-close");
                    if (closeBtn) closeBtn.click();
                });
            }
        });
    }

    // Initialize tooltips and popovers
    [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]')).map(function(e) {
        return new bootstrap.Tooltip(e);
    });

    [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]')).map(function(e) {
        return new bootstrap.Popover(e);
    });

    // Reset layout
    document.getElementById("reset-layout") &&
        document.getElementById("reset-layout").addEventListener("click", function() {
            sessionStorage.clear();
            window.location.reload();
        });

    // Initialize toast notifications
    var z = document.querySelectorAll("[data-toast]");
    Array.from(z).forEach(function(a) {
        a.addEventListener("click", function() {
            var e = {}, t = a.attributes;
            t["data-toast-text"] && (e.text = t["data-toast-text"].value.toString());
            t["data-toast-gravity"] && (e.gravity = t["data-toast-gravity"].value.toString());
            t["data-toast-position"] && (e.position = t["data-toast-position"].value.toString());
            t["data-toast-className"] && (e.className = t["data-toast-className"].value.toString());
            t["data-toast-duration"] && (e.duration = t["data-toast-duration"].value.toString());
            t["data-toast-close"] && (e.close = t["data-toast-close"].value.toString());
            t["data-toast-style"] && (e.style = t["data-toast-style"].value.toString());
            t["data-toast-offset"] && (e.offset = t["data-toast-offset"]);

            Toastify({
                newWindow: true,
                text: e.text,
                gravity: e.gravity,
                position: e.position,
                className: "bg-" + e.className,
                stopOnFocus: true,
                offset: {
                    x: e.offset ? 50 : 0,
                    y: e.offset ? 10 : 0
                },
                duration: e.duration,
                close: "close" == e.close,
                style: "style" == e.style ? {
                    background: "linear-gradient(to right, var(--vz-success), var(--vz-primary))"
                } : ""
            }).showToast();
        });
    });

    // Initialize choices
    z = document.querySelectorAll("[data-choices]");
    Array.from(z).forEach(function(e) {
        var t = {}, a = e.attributes;
        a["data-choices-groups"] && (t.placeholderValue = "This is a placeholder set in the config");
        a["data-choices-search-false"] && (t.searchEnabled = false);
        a["data-choices-search-true"] && (t.searchEnabled = true);
        a["data-choices-removeItem"] && (t.removeItemButton = true);
        a["data-choices-sorting-false"] && (t.shouldSort = false);
        a["data-choices-sorting-true"] && (t.shouldSort = true);
        a["data-choices-multiple-remove"] && (t.removeItemButton = true);
        a["data-choices-limit"] && (t.maxItemCount = a["data-choices-limit"].value.toString());
        a["data-choices-limit"] && (t.maxItemCount = a["data-choices-limit"].value.toString());
        a["data-choices-editItem-true"] && (t.maxItemCount = true);
        a["data-choices-editItem-false"] && (t.maxItemCount = false);
        a["data-choices-text-unique-true"] && (t.duplicateItemsAllowed = false);
        a["data-choices-text-disabled-true"] && (t.addItems = false);
        a["data-choices-text-disabled-true"] ? new Choices(e, t).disable() : new Choices(e, t);
    });

    // Initialize date/time pickers
    z = document.querySelectorAll("[data-provider]");
    Array.from(z).forEach(function(e) {
        var t, a, n;
        "flatpickr" == e.getAttribute("data-provider") ?
            (n = e.attributes,
            (t = {}).disableMobile = "true",
            n["data-date-format"] && (t.dateFormat = n["data-date-format"].value.toString()),
            n["data-enable-time"] && (t.enableTime = true, t.dateFormat = n["data-date-format"].value.toString() + " H:i"),
            n["data-altFormat"] && (t.altInput = true, t.altFormat = n["data-altFormat"].value.toString()),
            n["data-minDate"] && (t.minDate = n["data-minDate"].value.toString(), t.dateFormat = n["data-date-format"].value.toString()),
            n["data-maxDate"] && (t.maxDate = n["data-maxDate"].value.toString(), t.dateFormat = n["data-date-format"].value.toString()),
            n["data-deafult-date"] && (t.defaultDate = n["data-deafult-date"].value.toString(), t.dateFormat = n["data-date-format"].value.toString()),
            n["data-multiple-date"] && (t.mode = "multiple", t.dateFormat = n["data-date-format"].value.toString()),
            n["data-range-date"] && (t.mode = "range", t.dateFormat = n["data-date-format"].value.toString()),
            n["data-inline-date"] && (t.inline = true, t.defaultDate = n["data-deafult-date"].value.toString(), t.dateFormat = n["data-date-format"].value.toString()),
            n["data-disable-date"] && ((a = []).push(n["data-disable-date"].value), t.disable = a.toString().split(",")),
            n["data-week-number"] && ((a = []).push(n["data-week-number"].value), t.weekNumbers = true),
            flatpickr(e, t)) :
            "timepickr" == e.getAttribute("data-provider") &&
                (a = {},
                (n = e.attributes)["data-time-basic"] && (a.enableTime = true, a.noCalendar = true, a.dateFormat = "H:i"),
                n["data-time-hrs"] && (a.enableTime = true, a.noCalendar = true, a.dateFormat = "H:i", a.time_24hr = true),
                n["data-min-time"] && (a.enableTime = true, a.noCalendar = true, a.dateFormat = "H:i", a.minTime = n["data-min-time"].value.toString()),
                n["data-max-time"] && (a.enableTime = true, a.noCalendar = true, a.dateFormat = "H:i", a.minTime = n["data-max-time"].value.toString()),
                n["data-default-time"] && (a.enableTime = true, a.noCalendar = true, a.dateFormat = "H:i", a.defaultDate = n["data-default-time"].value.toString()),
                n["data-time-inline"] && (a.enableTime = true, a.noCalendar = true, a.defaultDate = n["data-time-inline"].value.toString(), a.inline = true),
                flatpickr(e, a));
    });

    // Handle tab clicks in dropdowns
    Array.from(document.querySelectorAll('.dropdown-menu a[data-bs-toggle="tab"]')).forEach(function(e) {
        e.addEventListener("click", function(e) {
            e.stopPropagation();
            bootstrap.Tab.getInstance(e.target).show();
        });
    });

    // Handle window resize for sidebar
    var q;
    window.addEventListener("resize", function() {
        q && clearTimeout(q);
        q = setTimeout(P, 2e3);
    });
}();

// Back to top functionality
var mybutton = document.getElementById("back-to-top");

function scrollFunction() {
    if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
        mybutton.style.display = "block";
    } else {
        mybutton.style.display = "none";
    }
}

function topFunction() {
    document.body.scrollTop = 0;
    document.documentElement.scrollTop = 0;
}

if (mybutton) {
    window.onscroll = function() {
        scrollFunction();
    };
}
