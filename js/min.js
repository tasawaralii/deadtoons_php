!function(o) {
    "use strict";
    o.fn.fitVids = function(t) {
        var e, i, n = {
            customSelector: null,
            ignore: null
        };
        return document.getElementById("fit-vids-style") || (e = document.head || document.getElementsByTagName("head")[0],
        (i = document.createElement("div")).innerHTML = '<p>x</p><style id="fit-vids-style">.fluid-width-video-wrapper{width:100%;position:relative;padding:0;}.fluid-width-video-wrapper iframe,.fluid-width-video-wrapper object,.fluid-width-video-wrapper embed {position:absolute;top:0;left:0;width:100%;height:100%;}</style>',
        e.appendChild(i.childNodes[1])),
        t && o.extend(n, t),
        this.each(function() {
            var t = ['iframe[src*="player.vimeo.com"]', 'iframe[src*="youtube.com"]', 'iframe[src*="youtube-nocookie.com"]', 'iframe[src*="kickstarter.com"][src*="video.html"]', "object", "embed"]
              , s = (n.customSelector && t.push(n.customSelector),
            ".fitvidsignore")
              , t = (n.ignore && (s = s + ", " + n.ignore),
            o(this).find(t.join(",")));
            (t = (t = t.not("object object")).not(s)).each(function(t) {
                var e, i = o(this);
                0 < i.parents(s).length || "embed" === this.tagName.toLowerCase() && i.parent("object").length || i.parent(".fluid-width-video-wrapper").length || (i.css("height") || i.css("width") || !isNaN(i.attr("height")) && !isNaN(i.attr("width")) || (i.attr("height", 9),
                i.attr("width", 16)),
                e = ("object" === this.tagName.toLowerCase() || i.attr("height") && !isNaN(parseInt(i.attr("height"), 10)) ? parseInt(i.attr("height"), 10) : i.height()) / (isNaN(parseInt(i.attr("width"), 10)) ? i.width() : parseInt(i.attr("width"), 10)),
                i.attr("id") || i.attr("id", "fitvid" + t),
                i.wrap('<div class="fluid-width-video-wrapper"></div>').parent(".fluid-width-video-wrapper").css("padding-top", 100 * e + "%"),
                i.removeAttr("height").removeAttr("width"))
            })
        })
    }
}(window.jQuery || window.Zepto),
function(t) {
    "function" == typeof define && define.amd ? define(["jquery"], t) : "object" == typeof exports ? t(require("jquery")) : t(window.jQuery || window.Zepto)
}(function(h) {
    function t() {}
    function d(t, e) {
        f.ev.on(i + t + b, e)
    }
    function c(t, e, i, s) {
        var n = document.createElement("div");
        return n.className = "mfp-" + t,
        i && (n.innerHTML = i),
        s ? e && e.appendChild(n) : (n = h(n),
        e && n.appendTo(e)),
        n
    }
    function u(t, e) {
        f.ev.triggerHandler(i + t, e),
        f.st.callbacks && (t = t.charAt(0).toLowerCase() + t.slice(1),
        f.st.callbacks[t] && f.st.callbacks[t].apply(f, h.isArray(e) ? e : [e]))
    }
    function p(t) {
        return t === B && f.currTemplate.closeBtn || (f.currTemplate.closeBtn = h(f.st.closeMarkup.replace("%title%", f.st.tClose)),
        B = t),
        f.currTemplate.closeBtn
    }
    function o() {
        h.magnificPopup.instance || ((f = new t).init(),
        h.magnificPopup.instance = f)
    }
    function L() {
        v && (l.after(v.addClass(a)).detach(),
        v = null)
    }
    function n() {
        e && h(document.body).removeClass(e)
    }
    function A() {
        n(),
        f.req && f.req.abort()
    }
    var f, s, m, r, g, B, a, l, v, e, w = "Close", H = "BeforeClose", y = "MarkupParse", _ = "Open", F = "Change", i = "mfp", b = "." + i, C = "mfp-ready", N = "mfp-removing", x = "mfp-prevent-close", k = !!window.jQuery, T = h(window), E = (h.magnificPopup = {
        instance: null,
        proto: t.prototype = {
            constructor: t,
            init: function() {
                var t = navigator.appVersion;
                f.isIE7 = -1 !== t.indexOf("MSIE 7."),
                f.isIE8 = -1 !== t.indexOf("MSIE 8."),
                f.isLowIE = f.isIE7 || f.isIE8,
                f.isAndroid = /android/gi.test(t),
                f.isIOS = /iphone|ipad|ipod/gi.test(t),
                f.supportsTransition = function() {
                    var t = document.createElement("p").style
                      , e = ["ms", "O", "Moz", "Webkit"];
                    if (void 0 !== t.transition)
                        return !0;
                    for (; e.length; )
                        if (e.pop() + "Transition"in t)
                            return !0;
                    return !1
                }(),
                f.probablyMobile = f.isAndroid || f.isIOS || /(Opera Mini)|Kindle|webOS|BlackBerry|(Opera Mobi)|(Windows Phone)|IEMobile/i.test(navigator.userAgent),
                m = h(document),
                f.popupsCache = {}
            },
            open: function(t) {
                if (!1 === t.isObj) {
                    f.items = t.items.toArray(),
                    f.index = 0;
                    for (var e, i = t.items, s = 0; s < i.length; s++)
                        if ((e = (e = i[s]).parsed ? e.el[0] : e) === t.el[0]) {
                            f.index = s;
                            break
                        }
                } else
                    f.items = h.isArray(t.items) ? t.items : [t.items],
                    f.index = t.index || 0;
                if (!f.isOpen) {
                    f.types = [],
                    g = "",
                    t.mainEl && t.mainEl.length ? f.ev = t.mainEl.eq(0) : f.ev = m,
                    t.key ? (f.popupsCache[t.key] || (f.popupsCache[t.key] = {}),
                    f.currTemplate = f.popupsCache[t.key]) : f.currTemplate = {},
                    f.st = h.extend(!0, {}, h.magnificPopup.defaults, t),
                    f.fixedContentPos = "auto" === f.st.fixedContentPos ? !f.probablyMobile : f.st.fixedContentPos,
                    f.st.modal && (f.st.closeOnContentClick = !1,
                    f.st.closeOnBgClick = !1,
                    f.st.showCloseBtn = !1,
                    f.st.enableEscapeKey = !1),
                    f.bgOverlay || (f.bgOverlay = c("bg").on("click" + b, function() {
                        f.close()
                    }),
                    f.wrap = c("wrap").attr("tabindex", -1).on("click" + b, function(t) {
                        f._checkIfClose(t.target) && f.close()
                    }),
                    f.container = c("container", f.wrap)),
                    f.contentContainer = c("content"),
                    f.st.preloader && (f.preloader = c("preloader", f.container, f.st.tLoading));
                    var n = h.magnificPopup.modules;
                    for (s = 0; s < n.length; s++) {
                        var o = (o = n[s]).charAt(0).toUpperCase() + o.slice(1);
                        f["init" + o].call(f)
                    }
                    u("BeforeOpen"),
                    f.st.showCloseBtn && (f.st.closeBtnInside ? (d(y, function(t, e, i, s) {
                        i.close_replaceWith = p(s.type)
                    }),
                    g += " mfp-close-btn-in") : f.wrap.append(p())),
                    f.st.alignTop && (g += " mfp-align-top"),
                    f.fixedContentPos ? f.wrap.css({
                        overflow: f.st.overflowY,
                        overflowX: "hidden",
                        overflowY: f.st.overflowY
                    }) : f.wrap.css({
                        top: T.scrollTop(),
                        position: "absolute"
                    }),
                    !1 !== f.st.fixedBgPos && ("auto" !== f.st.fixedBgPos || f.fixedContentPos) || f.bgOverlay.css({
                        height: m.height(),
                        position: "absolute"
                    }),
                    f.st.enableEscapeKey && m.on("keyup" + b, function(t) {
                        27 === t.keyCode && f.close()
                    }),
                    T.on("resize" + b, function() {
                        f.updateSize()
                    }),
                    f.st.closeOnContentClick || (g += " mfp-auto-cursor"),
                    g && f.wrap.addClass(g);
                    var r = f.wH = T.height()
                      , a = {}
                      , l = (f.fixedContentPos && f._hasScrollBar(r) && (l = f._getScrollbarSize()) && (a.marginRight = l),
                    f.fixedContentPos && (f.isIE7 ? h("body, html").css("overflow", "hidden") : a.overflow = "hidden"),
                    f.st.mainClass);
                    return f.isIE7 && (l += " mfp-ie7"),
                    l && f._addClassToMFP(l),
                    f.updateItemHTML(),
                    u("BuildControls"),
                    h("html").css(a),
                    f.bgOverlay.add(f.wrap).prependTo(f.st.prependTo || h(document.body)),
                    f._lastFocusedEl = document.activeElement,
                    setTimeout(function() {
                        f.content ? (f._addClassToMFP(C),
                        f._setFocus()) : f.bgOverlay.addClass(C),
                        m.on("focusin" + b, f._onFocusIn)
                    }, 16),
                    f.isOpen = !0,
                    f.updateSize(r),
                    u(_),
                    t
                }
                f.updateItemHTML()
            },
            close: function() {
                f.isOpen && (u(H),
                f.isOpen = !1,
                f.st.removalDelay && !f.isLowIE && f.supportsTransition ? (f._addClassToMFP(N),
                setTimeout(function() {
                    f._close()
                }, f.st.removalDelay)) : f._close())
            },
            _close: function() {
                u(w);
                var t = N + " " + C + " ";
                f.bgOverlay.detach(),
                f.wrap.detach(),
                f.container.empty(),
                f.st.mainClass && (t += f.st.mainClass + " "),
                f._removeClassFromMFP(t),
                f.fixedContentPos && (t = {
                    marginRight: ""
                },
                f.isIE7 ? h("body, html").css("overflow", "") : t.overflow = "",
                h("html").css(t)),
                m.off("keyup.mfp focusin" + b),
                f.ev.off(b),
                f.wrap.attr("class", "mfp-wrap").removeAttr("style"),
                f.bgOverlay.attr("class", "mfp-bg"),
                f.container.attr("class", "mfp-container"),
                !f.st.showCloseBtn || f.st.closeBtnInside && !0 !== f.currTemplate[f.currItem.type] || f.currTemplate.closeBtn && f.currTemplate.closeBtn.detach(),
                f._lastFocusedEl && h(f._lastFocusedEl).focus(),
                f.currItem = null,
                f.content = null,
                f.currTemplate = null,
                f.prevHeight = 0,
                u("AfterClose")
            },
            updateSize: function(t) {
                var e;
                f.isIOS ? (e = document.documentElement.clientWidth / window.innerWidth,
                e = window.innerHeight * e,
                f.wrap.css("height", e),
                f.wH = e) : f.wH = t || T.height(),
                f.fixedContentPos || f.wrap.css("height", f.wH),
                u("Resize")
            },
            updateItemHTML: function() {
                var t = f.items[f.index]
                  , e = (f.contentContainer.detach(),
                f.content && f.content.detach(),
                (t = t.parsed ? t : f.parseEl(f.index)).type)
                  , i = (u("BeforeChange", [f.currItem ? f.currItem.type : "", e]),
                f.currItem = t,
                f.currTemplate[e] || (i = !!f.st[e] && f.st[e].markup,
                u("FirstMarkupParse", i),
                f.currTemplate[e] = !i || h(i)),
                r && r !== t.type && f.container.removeClass("mfp-" + r + "-holder"),
                f["get" + e.charAt(0).toUpperCase() + e.slice(1)](t, f.currTemplate[e]));
                f.appendContent(i, e),
                t.preloaded = !0,
                u(F, t),
                r = t.type,
                f.container.prepend(f.contentContainer),
                u("AfterChange")
            },
            appendContent: function(t, e) {
                (f.content = t) ? f.st.showCloseBtn && f.st.closeBtnInside && !0 === f.currTemplate[e] ? f.content.find(".mfp-close").length || f.content.append(p()) : f.content = t : f.content = "",
                u("BeforeAppend"),
                f.container.addClass("mfp-" + e + "-holder"),
                f.contentContainer.append(f.content)
            },
            parseEl: function(t) {
                var e, i = f.items[t];
                if ((i = i.tagName ? {
                    el: h(i)
                } : (e = i.type,
                {
                    data: i,
                    src: i.src
                })).el) {
                    for (var s = f.types, n = 0; n < s.length; n++)
                        if (i.el.hasClass("mfp-" + s[n])) {
                            e = s[n];
                            break
                        }
                    i.src = i.el.attr("data-mfp-src"),
                    i.src || (i.src = i.el.attr("href"))
                }
                return i.type = e || f.st.type || "inline",
                i.index = t,
                i.parsed = !0,
                f.items[t] = i,
                u("ElementParse", i),
                f.items[t]
            },
            addGroup: function(e, i) {
                function t(t) {
                    t.mfpEl = this,
                    f._openClick(t, e, i)
                }
                var s = "click.magnificPopup";
                (i = i || {}).mainEl = e,
                i.items ? (i.isObj = !0,
                e.off(s).on(s, t)) : (i.isObj = !1,
                i.delegate ? e.off(s).on(s, i.delegate, t) : (i.items = e).off(s).on(s, t))
            },
            _openClick: function(t, e, i) {
                if ((void 0 !== i.midClick ? i : h.magnificPopup.defaults).midClick || 2 !== t.which && !t.ctrlKey && !t.metaKey) {
                    var s = (void 0 !== i.disableOn ? i : h.magnificPopup.defaults).disableOn;
                    if (s)
                        if (h.isFunction(s)) {
                            if (!s.call(f))
                                return !0
                        } else if (T.width() < s)
                            return !0;
                    t.type && (t.preventDefault(),
                    f.isOpen && t.stopPropagation()),
                    i.el = h(t.mfpEl),
                    i.delegate && (i.items = e.find(i.delegate)),
                    f.open(i)
                }
            },
            updateStatus: function(t, e) {
                var i;
                f.preloader && (s !== t && f.container.removeClass("mfp-s-" + s),
                i = {
                    status: t,
                    text: e = e || "loading" !== t ? e : f.st.tLoading
                },
                u("UpdateStatus", i),
                t = i.status,
                f.preloader.html(e = i.text),
                f.preloader.find("a").on("click", function(t) {
                    t.stopImmediatePropagation()
                }),
                f.container.addClass("mfp-s-" + t),
                s = t)
            },
            _checkIfClose: function(t) {
                if (!h(t).hasClass(x)) {
                    var e = f.st.closeOnContentClick
                      , i = f.st.closeOnBgClick;
                    if (e && i)
                        return !0;
                    if (!f.content || h(t).hasClass("mfp-close") || f.preloader && t === f.preloader[0])
                        return !0;
                    if (t === f.content[0] || h.contains(f.content[0], t)) {
                        if (e)
                            return !0
                    } else if (i && h.contains(document, t))
                        return !0;
                    return !1
                }
            },
            _addClassToMFP: function(t) {
                f.bgOverlay.addClass(t),
                f.wrap.addClass(t)
            },
            _removeClassFromMFP: function(t) {
                this.bgOverlay.removeClass(t),
                f.wrap.removeClass(t)
            },
            _hasScrollBar: function(t) {
                return (f.isIE7 ? m.height() : document.body.scrollHeight) > (t || T.height())
            },
            _setFocus: function() {
                (f.st.focus ? f.content.find(f.st.focus).eq(0) : f.wrap).focus()
            },
            _onFocusIn: function(t) {
                if (t.target !== f.wrap[0] && !h.contains(f.wrap[0], t.target))
                    return f._setFocus(),
                    !1
            },
            _parseMarkup: function(n, t, e) {
                var o;
                e.data && (t = h.extend(e.data, t)),
                u(y, [n, t, e]),
                h.each(t, function(t, e) {
                    if (void 0 === e || !1 === e)
                        return !0;
                    var i, s;
                    1 < (o = t.split("_")).length ? 0 < (i = n.find(b + "-" + o[0])).length && ("replaceWith" === (s = o[1]) ? i[0] !== e[0] && i.replaceWith(e) : "img" === s ? i.is("img") ? i.attr("src", e) : i.replaceWith('<img src="' + e + '" class="' + i.attr("class") + '" />') : i.attr(o[1], e)) : n.find(b + "-" + t).html(e)
                })
            },
            _getScrollbarSize: function() {
                var t;
                return void 0 === f.scrollbarSize && ((t = document.createElement("div")).style.cssText = "width: 99px; height: 99px; overflow: scroll; position: absolute; top: -9999px;",
                document.body.appendChild(t),
                f.scrollbarSize = t.offsetWidth - t.clientWidth,
                document.body.removeChild(t)),
                f.scrollbarSize
            }
        },
        modules: [],
        open: function(t, e) {
            return o(),
            (t = t ? h.extend(!0, {}, t) : {}).isObj = !0,
            t.index = e || 0,
            this.instance.open(t)
        },
        close: function() {
            return h.magnificPopup.instance && h.magnificPopup.instance.close()
        },
        registerModule: function(t, e) {
            e.options && (h.magnificPopup.defaults[t] = e.options),
            h.extend(this.proto, e.proto),
            this.modules.push(t)
        },
        defaults: {
            disableOn: 0,
            key: null,
            midClick: !1,
            mainClass: "",
            preloader: !0,
            focus: "",
            closeOnContentClick: !1,
            closeOnBgClick: !0,
            closeBtnInside: !0,
            showCloseBtn: !0,
            enableEscapeKey: !0,
            modal: !1,
            alignTop: !1,
            removalDelay: 0,
            prependTo: null,
            fixedContentPos: "auto",
            fixedBgPos: "auto",
            overflowY: "auto",
            closeMarkup: '<button title="%title%" type="button" class="mfp-close">&times;</button>',
            tClose: "Close (Esc)",
            tLoading: "Loading..."
        }
    },
    h.fn.magnificPopup = function(t) {
        o();
        var e, i, s, n = h(this);
        return "string" == typeof t ? "open" === t ? (e = k ? n.data("magnificPopup") : n[0].magnificPopup,
        i = parseInt(arguments[1], 10) || 0,
        s = e.items ? e.items[i] : (s = n,
        (s = e.delegate ? s.find(e.delegate) : s).eq(i)),
        f._openClick({
            mfpEl: s
        }, n, e)) : f.isOpen && f[t].apply(f, Array.prototype.slice.call(arguments, 1)) : (t = h.extend(!0, {}, t),
        k ? n.data("magnificPopup", t) : n[0].magnificPopup = t,
        f.addGroup(n, t)),
        n
    }
    ,
    "inline"), S = (h.magnificPopup.registerModule(E, {
        options: {
            hiddenClass: "hide",
            markup: "",
            tNotFound: "Content not found"
        },
        proto: {
            initInline: function() {
                f.types.push(E),
                d(w + "." + E, function() {
                    L()
                })
            },
            getInline: function(t, e) {
                var i, s, n;
                return L(),
                t.src ? (i = f.st.inline,
                (s = h(t.src)).length ? ((n = s[0].parentNode) && n.tagName && (l || (a = i.hiddenClass,
                l = c(a),
                a = "mfp-" + a),
                v = s.after(l).detach().removeClass(a)),
                f.updateStatus("ready")) : (f.updateStatus("error", i.tNotFound),
                s = h("<div>")),
                t.inlineElement = s) : (f.updateStatus("ready"),
                f._parseMarkup(e, {}, t),
                e)
            }
        }
    }),
    "ajax");
    h.magnificPopup.registerModule(S, {
        options: {
            settings: null,
            cursor: "mfp-ajax-cur",
            tError: '<a href="%url%">The content</a> could not be loaded.'
        },
        proto: {
            initAjax: function() {
                f.types.push(S),
                e = f.st.ajax.cursor,
                d(w + "." + S, A),
                d("BeforeChange." + S, A)
            },
            getAjax: function(s) {
                e && h(document.body).addClass(e),
                f.updateStatus("loading");
                var t = h.extend({
                    url: s.src,
                    success: function(t, e, i) {
                        t = {
                            data: t,
                            xhr: i
                        };
                        u("ParseAjax", t),
                        f.appendContent(h(t.data), S),
                        s.finished = !0,
                        n(),
                        f._setFocus(),
                        setTimeout(function() {
                            f.wrap.addClass(C)
                        }, 16),
                        f.updateStatus("ready"),
                        u("AjaxContentAdded")
                    },
                    error: function() {
                        n(),
                        s.finished = s.loadError = !0,
                        f.updateStatus("error", f.st.ajax.tError.replace("%url%", s.src))
                    }
                }, f.st.ajax.settings);
                return f.req = h.ajax(t),
                ""
            }
        }
    });
    var I;
    h.magnificPopup.registerModule("image", {
        options: {
            markup: '<div class="mfp-figure"><div class="mfp-close"></div><figure><div class="mfp-img"></div><figcaption><div class="mfp-bottom-bar"><div class="mfp-title"></div><div class="mfp-counter"></div></div></figcaption></figure></div>',
            cursor: "mfp-zoom-out-cur",
            titleSrc: "title",
            verticalFit: !0,
            tError: '<a href="%url%">The image</a> could not be loaded.'
        },
        proto: {
            initImage: function() {
                var t = f.st.image
                  , e = ".image";
                f.types.push("image"),
                d(_ + e, function() {
                    "image" === f.currItem.type && t.cursor && h(document.body).addClass(t.cursor)
                }),
                d(w + e, function() {
                    t.cursor && h(document.body).removeClass(t.cursor),
                    T.off("resize" + b)
                }),
                d("Resize" + e, f.resizeImage),
                f.isLowIE && d("AfterChange", f.resizeImage)
            },
            resizeImage: function() {
                var t, e = f.currItem;
                e && e.img && f.st.image.verticalFit && (t = 0,
                f.isLowIE && (t = parseInt(e.img.css("padding-top"), 10) + parseInt(e.img.css("padding-bottom"), 10)),
                e.img.css("max-height", f.wH - t))
            },
            _onImageHasSize: function(t) {
                t.img && (t.hasSize = !0,
                I && clearInterval(I),
                t.isCheckingImgSize = !1,
                u("ImageHasSize", t),
                t.imgHidden && (f.content && f.content.removeClass("mfp-loading"),
                t.imgHidden = !1))
            },
            findImageSize: function(e) {
                function i(t) {
                    I && clearInterval(I),
                    I = setInterval(function() {
                        0 < n.naturalWidth ? f._onImageHasSize(e) : (200 < s && clearInterval(I),
                        3 === ++s ? i(10) : 40 === s ? i(50) : 100 === s && i(500))
                    }, t)
                }
                var s = 0
                  , n = e.img[0];
                i(1)
            },
            getImage: function(t, e) {
                function i() {
                    t && (t.img[0].complete ? (t.img.off(".mfploader"),
                    t === f.currItem && (f._onImageHasSize(t),
                    f.updateStatus("ready")),
                    t.hasSize = !0,
                    t.loaded = !0,
                    u("ImageLoadComplete")) : ++o < 200 ? setTimeout(i, 100) : s())
                }
                function s() {
                    t && (t.img.off(".mfploader"),
                    t === f.currItem && (f._onImageHasSize(t),
                    f.updateStatus("error", r.tError.replace("%url%", t.src))),
                    t.hasSize = !0,
                    t.loaded = !0,
                    t.loadError = !0)
                }
                var n, o = 0, r = f.st.image, a = e.find(".mfp-img");
                return a.length && ((n = document.createElement("img")).className = "mfp-img",
                t.el && t.el.find("img").length && (n.alt = t.el.find("img").attr("alt")),
                t.img = h(n).on("load.mfploader", i).on("error.mfploader", s),
                n.src = t.src,
                a.is("img") && (t.img = t.img.clone()),
                0 < (n = t.img[0]).naturalWidth ? t.hasSize = !0 : n.width || (t.hasSize = !1)),
                f._parseMarkup(e, {
                    title: function(t) {
                        if (t.data && void 0 !== t.data.title)
                            return t.data.title;
                        var e = f.st.image.titleSrc;
                        if (e) {
                            if (h.isFunction(e))
                                return e.call(f, t);
                            if (t.el)
                                return t.el.attr(e) || ""
                        }
                        return ""
                    }(t),
                    img_replaceWith: t.img
                }, t),
                f.resizeImage(),
                t.hasSize ? (I && clearInterval(I),
                t.loadError ? (e.addClass("mfp-loading"),
                f.updateStatus("error", r.tError.replace("%url%", t.src))) : (e.removeClass("mfp-loading"),
                f.updateStatus("ready"))) : (f.updateStatus("loading"),
                t.loading = !0,
                t.hasSize || (t.imgHidden = !0,
                e.addClass("mfp-loading"),
                f.findImageSize(t))),
                e
            }
        }
    });
    function P(t) {
        var e;
        f.currTemplate[O] && (e = f.currTemplate[O].find("iframe")).length && (t || (e[0].src = "//about:blank"),
        f.isIE8 && e.css("display", t ? "block" : "none"))
    }
    function $(t) {
        var e = f.items.length;
        return e - 1 < t ? t - e : t < 0 ? e + t : t
    }
    function W(t, e, i) {
        return t.replace(/%curr%/gi, e + 1).replace(/%total%/gi, i)
    }
    h.magnificPopup.registerModule("zoom", {
        options: {
            enabled: !1,
            easing: "ease-in-out",
            duration: 300,
            opener: function(t) {
                return t.is("img") ? t : t.find("img")
            }
        },
        proto: {
            initZoom: function() {
                var t, e, i, s, n, o, r = f.st.zoom, a = ".zoom";
                r.enabled && f.supportsTransition && (e = r.duration,
                i = function(t) {
                    var t = t.clone().removeAttr("style").removeAttr("class").addClass("mfp-animated-image")
                      , e = "all " + r.duration / 1e3 + "s " + r.easing
                      , i = {
                        position: "fixed",
                        zIndex: 9999,
                        left: 0,
                        top: 0,
                        "-webkit-backface-visibility": "hidden"
                    }
                      , s = "transition";
                    return i["-webkit-" + s] = i["-moz-" + s] = i["-o-" + s] = i[s] = e,
                    t.css(i),
                    t
                }
                ,
                s = function() {
                    f.content.css("visibility", "visible")
                }
                ,
                d("BuildControls" + a, function() {
                    f._allowZoom() && (clearTimeout(n),
                    f.content.css("visibility", "hidden"),
                    (t = f._getItemToZoom()) ? ((o = i(t)).css(f._getOffset()),
                    f.wrap.append(o),
                    n = setTimeout(function() {
                        o.css(f._getOffset(!0)),
                        n = setTimeout(function() {
                            s(),
                            setTimeout(function() {
                                o.remove(),
                                t = o = null,
                                u("ZoomAnimationEnded")
                            }, 16)
                        }, e)
                    }, 16)) : s())
                }),
                d(H + a, function() {
                    if (f._allowZoom()) {
                        if (clearTimeout(n),
                        f.st.removalDelay = e,
                        !t) {
                            if (!(t = f._getItemToZoom()))
                                return;
                            o = i(t)
                        }
                        o.css(f._getOffset(!0)),
                        f.wrap.append(o),
                        f.content.css("visibility", "hidden"),
                        setTimeout(function() {
                            o.css(f._getOffset())
                        }, 16)
                    }
                }),
                d(w + a, function() {
                    f._allowZoom() && (s(),
                    o && o.remove(),
                    t = null)
                }))
            },
            _allowZoom: function() {
                return "image" === f.currItem.type
            },
            _getItemToZoom: function() {
                return !!f.currItem.hasSize && f.currItem.img
            },
            _getOffset: function(t) {
                var t = t ? f.currItem.img : f.st.zoom.opener(f.currItem.el || f.currItem)
                  , e = t.offset()
                  , i = parseInt(t.css("padding-top"), 10)
                  , s = parseInt(t.css("padding-bottom"), 10)
                  , t = (e.top -= h(window).scrollTop() - i,
                {
                    width: t.width(),
                    height: (k ? t.innerHeight() : t[0].offsetHeight) - s - i
                });
                return (z = void 0 === z ? void 0 !== document.createElement("p").style.MozTransform : z) ? t["-moz-transform"] = t.transform = "translate(" + e.left + "px," + e.top + "px)" : (t.left = e.left,
                t.top = e.top),
                t
            }
        }
    });
    var z, M, D, O = "iframe", j = (h.magnificPopup.registerModule(O, {
        options: {
            markup: '<div class="mfp-iframe-scaler"><div class="mfp-close"></div><iframe class="mfp-iframe" src="//about:blank" frameborder="0" allowfullscreen></iframe></div>',
            srcAction: "iframe_src",
            patterns: {
                youtube: {
                    index: "youtube.com",
                    id: "v=",
                    src: "//www.youtube.com/embed/%id%?autoplay=1"
                },
                vimeo: {
                    index: "vimeo.com/",
                    id: "/",
                    src: "//player.vimeo.com/video/%id%?autoplay=1"
                },
                gmaps: {
                    index: "//maps.google.",
                    src: "%id%&output=embed"
                }
            }
        },
        proto: {
            initIframe: function() {
                f.types.push(O),
                d("BeforeChange", function(t, e, i) {
                    e !== i && (e === O ? P() : i === O && P(!0))
                }),
                d(w + "." + O, function() {
                    P()
                })
            },
            getIframe: function(t, e) {
                var i = t.src
                  , s = f.st.iframe
                  , n = (h.each(s.patterns, function() {
                    if (-1 < i.indexOf(this.index))
                        return this.id && (i = "string" == typeof this.id ? i.substr(i.lastIndexOf(this.id) + this.id.length, i.length) : this.id.call(this, i)),
                        i = this.src.replace("%id%", i),
                        !1
                }),
                {});
                return s.srcAction && (n[s.srcAction] = i),
                f._parseMarkup(e, n, t),
                f.updateStatus("ready"),
                e
            }
        }
    }),
    h.magnificPopup.registerModule("gallery", {
        options: {
            enabled: !1,
            arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir%"></button>',
            preload: [0, 2],
            navigateByImgClick: !0,
            arrows: !0,
            tPrev: "Previous (Left arrow key)",
            tNext: "Next (Right arrow key)",
            tCounter: "%curr% of %total%"
        },
        proto: {
            initGallery: function() {
                var o = f.st.gallery
                  , t = ".mfp-gallery"
                  , s = Boolean(h.fn.mfpFastClick);
                if (f.direction = !0,
                !o || !o.enabled)
                    return !1;
                g += " mfp-gallery",
                d(_ + t, function() {
                    o.navigateByImgClick && f.wrap.on("click" + t, ".mfp-img", function() {
                        if (1 < f.items.length)
                            return f.next(),
                            !1
                    }),
                    m.on("keydown" + t, function(t) {
                        37 === t.keyCode ? f.prev() : 39 === t.keyCode && f.next()
                    })
                }),
                d("UpdateStatus" + t, function(t, e) {
                    e.text && (e.text = W(e.text, f.currItem.index, f.items.length))
                }),
                d(y + t, function(t, e, i, s) {
                    var n = f.items.length;
                    i.counter = 1 < n ? W(o.tCounter, s.index, n) : ""
                }),
                d("BuildControls" + t, function() {
                    var t, e, i;
                    1 < f.items.length && o.arrows && !f.arrowLeft && (e = o.arrowMarkup,
                    t = f.arrowLeft = h(e.replace(/%title%/gi, o.tPrev).replace(/%dir%/gi, "left")).addClass(x),
                    e = f.arrowRight = h(e.replace(/%title%/gi, o.tNext).replace(/%dir%/gi, "right")).addClass(x),
                    t[i = s ? "mfpFastClick" : "click"](function() {
                        f.prev()
                    }),
                    e[i](function() {
                        f.next()
                    }),
                    f.isIE7 && (c("b", t[0], !1, !0),
                    c("a", t[0], !1, !0),
                    c("b", e[0], !1, !0),
                    c("a", e[0], !1, !0)),
                    f.container.append(t.add(e)))
                }),
                d(F + t, function() {
                    f._preloadTimeout && clearTimeout(f._preloadTimeout),
                    f._preloadTimeout = setTimeout(function() {
                        f.preloadNearbyImages(),
                        f._preloadTimeout = null
                    }, 16)
                }),
                d(w + t, function() {
                    m.off(t),
                    f.wrap.off("click" + t),
                    f.arrowLeft && s && f.arrowLeft.add(f.arrowRight).destroyMfpFastClick(),
                    f.arrowRight = f.arrowLeft = null
                })
            },
            next: function() {
                f.direction = !0,
                f.index = $(f.index + 1),
                f.updateItemHTML()
            },
            prev: function() {
                f.direction = !1,
                f.index = $(f.index - 1),
                f.updateItemHTML()
            },
            goTo: function(t) {
                f.direction = t >= f.index,
                f.index = t,
                f.updateItemHTML()
            },
            preloadNearbyImages: function() {
                for (var t = f.st.gallery.preload, e = Math.min(t[0], f.items.length), i = Math.min(t[1], f.items.length), s = 1; s <= (f.direction ? i : e); s++)
                    f._preloadItem(f.index + s);
                for (s = 1; s <= (f.direction ? e : i); s++)
                    f._preloadItem(f.index - s)
            },
            _preloadItem: function(t) {
                var e;
                t = $(t),
                f.items[t].preloaded || ((e = f.items[t]).parsed || (e = f.parseEl(t)),
                u("LazyLoad", e),
                "image" === e.type && (e.img = h('<img class="mfp-img" />').on("load.mfploader", function() {
                    e.hasSize = !0
                }).on("error.mfploader", function() {
                    e.hasSize = !0,
                    e.loadError = !0,
                    u("LazyLoadError", e)
                }).attr("src", e.src)),
                e.preloaded = !0)
            }
        }
    }),
    "retina");
    function X() {
        T.off("touchmove" + D + " touchend" + D)
    }
    h.magnificPopup.registerModule(j, {
        options: {
            replaceSrc: function(t) {
                return t.src.replace(/\.\w+$/, function(t) {
                    return "@2x" + t
                })
            },
            ratio: 1
        },
        proto: {
            initRetina: function() {
                var i, s;
                1 < window.devicePixelRatio && (i = f.st.retina,
                s = i.ratio,
                1 < (s = isNaN(s) ? s() : s) && (d("ImageHasSize." + j, function(t, e) {
                    e.img.css({
                        "max-width": e.img[0].naturalWidth / s,
                        width: "100%"
                    })
                }),
                d("ElementParse." + j, function(t, e) {
                    e.src = i.replaceSrc(e, s)
                })))
            }
        }
    }),
    M = "ontouchstart"in window,
    D = ".mfpFastClick",
    h.fn.mfpFastClick = function(l) {
        return h(this).each(function() {
            var e, i, s, n, o, r, a, t = h(this);
            M && t.on("touchstart" + D, function(t) {
                o = !1,
                a = 1,
                r = (t.originalEvent || t).touches[0],
                s = r.clientX,
                n = r.clientY,
                T.on("touchmove" + D, function(t) {
                    r = (t.originalEvent || t).touches,
                    a = r.length,
                    r = r[0],
                    (10 < Math.abs(r.clientX - s) || 10 < Math.abs(r.clientY - n)) && (o = !0,
                    X())
                }).on("touchend" + D, function(t) {
                    X(),
                    o || 1 < a || (e = !0,
                    t.preventDefault(),
                    clearTimeout(i),
                    i = setTimeout(function() {
                        e = !1
                    }, 1e3),
                    l())
                })
            }),
            t.on("click" + D, function() {
                e || l()
            })
        })
    }
    ,
    h.fn.destroyMfpFastClick = function() {
        h(this).off("touchstart" + D + " click" + D),
        M && T.off("touchmove" + D + " touchend" + D)
    }
    ,
    o()
}),
function() {
    var z = this.jQuery || window.jQuery
      , M = z(window);
    z.fn.stick_in_parent = function(t) {
        var C, e, i, s, n, x, k = (t = null == t ? {} : t).sticky_class, T = t.inner_scrolling, E = t.recalc_every, S = t.parent, I = t.offset_top, P = t.spacer, $ = t.bottoming;
        for (null == I && (I = 0),
        null == S && (S = void 0),
        null == T && (T = !0),
        null == k && (k = "is_stuck"),
        C = z(document),
        null == $ && ($ = !0),
        x = function(t) {
            var e, i;
            return window.getComputedStyle ? (t[0],
            e = window.getComputedStyle(t[0]),
            i = parseFloat(e.getPropertyValue("width")) + parseFloat(e.getPropertyValue("margin-left")) + parseFloat(e.getPropertyValue("margin-right")),
            "border-box" !== e.getPropertyValue("box-sizing") && (i += parseFloat(e.getPropertyValue("border-left-width")) + parseFloat(e.getPropertyValue("border-right-width")) + parseFloat(e.getPropertyValue("padding-left")) + parseFloat(e.getPropertyValue("padding-right"))),
            i) : t.outerWidth(!0)
        }
        ,
        i = function(o, r, a, l, h, d, c, u) {
            var p, t, f, m, g, v, w, y, e, _, b, s;
            if (!o.data("sticky_kit")) {
                if (o.data("sticky_kit", !0),
                g = C.height(),
                w = o.parent(),
                !(w = null != S ? w.closest(S) : w).length)
                    throw "failed to find stick parent";
                if (p = f = !1,
                (b = null != P ? P && o.closest(P) : z("<div />")) && b.css("position", o.css("position")),
                (y = function() {
                    var t, e, i;
                    if (!u)
                        return g = C.height(),
                        t = parseInt(w.css("border-top-width"), 10),
                        e = parseInt(w.css("padding-top"), 10),
                        r = parseInt(w.css("padding-bottom"), 10),
                        a = w.offset().top + t + e,
                        l = w.height(),
                        f && (p = f = !1,
                        null == P && (o.insertAfter(b),
                        b.detach()),
                        o.css({
                            position: "",
                            width: "",
                            bottom: ""
                        }).removeClass(k),
                        i = !0),
                        h = o.offset().top - (parseInt(o.css("margin-top"), 10) || 0) - I,
                        d = o.outerHeight(!0),
                        c = o.css("float"),
                        b && b.css({
                            width: x(o),
                            height: d,
                            display: o.css("display"),
                            "vertical-align": o.css("vertical-align"),
                            float: c
                        }),
                        i ? s() : void 0
                }
                )(),
                d !== l)
                    return m = void 0,
                    v = I,
                    _ = E,
                    s = function() {
                        var t, e, i, s, n;
                        if (!u)
                            return i = !1,
                            null != _ && --_ <= 0 && (_ = E,
                            y(),
                            i = !0),
                            i || C.height() === g || (y(),
                            i = !0),
                            i = M.scrollTop(),
                            null != m && (e = i - m),
                            m = i,
                            f ? ($ && (s = l + a < i + d + v,
                            p && !s && (p = !1,
                            o.css({
                                position: "fixed",
                                bottom: "",
                                top: ""
                            }).trigger("sticky_kit:unbottom"))),
                            i < h && (f = !1,
                            v = I,
                            null == P && ("left" !== c && "right" !== c || o.insertAfter(b),
                            b.detach()),
                            o.css(t = {
                                position: "",
                                width: ""
                            }).removeClass(k).trigger("sticky_kit:unstick")),
                            T && (n = M.height()) < d + I && (p || (v -= e,
                            v = Math.max(n - d, v),
                            v = Math.min(I, v)))) : h < i && (f = !0,
                            (t = {
                                position: "fixed"
                            }).width = "border-box" === o.css("box-sizing") ? o.outerWidth() + "px" : o.width() + "px",
                            o.css(t).addClass(k),
                            null == P && (o.after(b),
                            "left" !== c && "right" !== c || b.append(o)),
                            o.trigger("sticky_kit:stick")),
                            f && $ && (null == s && (s = l + a < i + d + v),
                            !p && s) ? (p = !0,
                            "static" === w.css("position") && w.css({
                                position: "relative"
                            }),
                            o.css({
                                position: "absolute",
                                bottom: r,
                                top: "auto"
                            }).trigger("sticky_kit:bottom")) : void 0
                    }
                    ,
                    e = function() {
                        if (!(document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement))
                            return y(),
                            s()
                    }
                    ,
                    t = function() {
                        if (u = !0,
                        M.off("touchmove", s),
                        M.off("scroll", s),
                        M.off("resize", e),
                        z(document.body).off("sticky_kit:recalc", e),
                        o.off("sticky_kit:detach", t),
                        o.removeData("sticky_kit"),
                        o.css({
                            position: "",
                            bottom: "",
                            width: ""
                        }),
                        w.position("position", ""),
                        f)
                            return null == P && ("left" !== c && "right" !== c || o.insertAfter(b),
                            b.remove()),
                            o.removeClass(k)
                    }
                    ,
                    M.on("touchmove", s),
                    M.on("scroll", s),
                    M.on("resize", e),
                    z(document.body).on("sticky_kit:recalc", e),
                    o.on("sticky_kit:detach", t),
                    setTimeout(s, 0)
            }
        }
        ,
        s = 0,
        n = this.length; s < n; s++)
            e = this[s],
            i(z(e));
        return this
    }
}
.call(this),
function(l, h, o, r) {
    function i(t, e) {
        this.settings = null,
        this.options = l.extend({}, i.Defaults, e),
        this.$element = l(t),
        this.drag = l.extend({}, s),
        this.state = l.extend({}, a),
        this.e = l.extend({}, d),
        this._plugins = {},
        this._supress = {},
        this._current = null,
        this._speed = null,
        this._coordinates = [],
        this._breakpoint = null,
        this._width = null,
        this._items = [],
        this._clones = [],
        this._mergers = [],
        this._invalidated = {},
        this._pipe = [],
        l.each(i.Plugins, l.proxy(function(t, e) {
            this._plugins[t[0].toLowerCase() + t.slice(1)] = new e(this)
        }, this)),
        l.each(i.Pipe, l.proxy(function(t, e) {
            this._pipe.push({
                filter: e.filter,
                run: l.proxy(e.run, this)
            })
        }, this)),
        this.setup(),
        this.initialize()
    }
    function n(t) {
        return t.touches !== r ? {
            x: t.touches[0].pageX,
            y: t.touches[0].pageY
        } : t.touches === r ? t.pageX !== r ? {
            x: t.pageX,
            y: t.pageY
        } : t.pageX === r ? {
            x: t.clientX,
            y: t.clientY
        } : void 0 : void 0
    }
    function t(t) {
        var e, i, s = o.createElement("div"), n = t;
        for (e in n)
            if (i = n[e],
            void 0 !== s.style[i])
                return s = null,
                [i, e];
        return [!1]
    }
    var s = {
        start: 0,
        startX: 0,
        startY: 0,
        current: 0,
        currentX: 0,
        currentY: 0,
        offsetX: 0,
        offsetY: 0,
        distance: null,
        startTime: 0,
        endTime: 0,
        updatedX: 0,
        targetEl: null
    }
      , a = {
        isTouch: !1,
        isScrolling: !1,
        isSwiping: !1,
        direction: !1,
        inMotion: !1
    }
      , d = {
        _onDragStart: null,
        _onDragMove: null,
        _onDragEnd: null,
        _transitionEnd: null,
        _resizer: null,
        _responsiveCall: null,
        _goToLoop: null,
        _checkVisibile: null
    };
    i.Defaults = {
        items: 3,
        loop: !1,
        center: !1,
        mouseDrag: !0,
        touchDrag: !0,
        pullDrag: !0,
        freeDrag: !1,
        margin: 0,
        stagePadding: 0,
        merge: !1,
        mergeFit: !0,
        autoWidth: !1,
        startPosition: 0,
        rtl: !1,
        smartSpeed: 250,
        fluidSpeed: !1,
        dragEndSpeed: !1,
        responsive: {},
        responsiveRefreshRate: 200,
        responsiveBaseElement: h,
        responsiveClass: !1,
        fallbackEasing: "swing",
        info: !1,
        nestedItemSelector: !1,
        itemElement: "div",
        stageElement: "div",
        themeClass: "owl-theme",
        baseClass: "owl-carousel",
        itemClass: "owl-item",
        centerClass: "center",
        activeClass: "active"
    },
    i.Width = {
        Default: "default",
        Inner: "inner",
        Outer: "outer"
    },
    i.Plugins = {},
    i.Pipe = [{
        filter: ["width", "items", "settings"],
        run: function(t) {
            t.current = this._items && this._items[this.relative(this._current)]
        }
    }, {
        filter: ["items", "settings"],
        run: function() {
            var t = this._clones;
            (this.$stage.children(".cloned").length !== t.length || !this.settings.loop && 0 < t.length) && (this.$stage.children(".cloned").remove(),
            this._clones = [])
        }
    }, {
        filter: ["items", "settings"],
        run: function() {
            for (var t = this._clones, e = this._items, i = this.settings.loop ? t.length - Math.max(2 * this.settings.items, 4) : 0, s = 0, n = Math.abs(i / 2); s < n; s++)
                0 < i ? (this.$stage.children().eq(e.length + t.length - 1).remove(),
                t.pop(),
                this.$stage.children().eq(0).remove(),
                t.pop()) : (t.push(t.length / 2),
                this.$stage.append(e[t[t.length - 1]].clone().addClass("cloned")),
                t.push(e.length - 1 - (t.length - 1) / 2),
                this.$stage.prepend(e[t[t.length - 1]].clone().addClass("cloned")))
        }
    }, {
        filter: ["width", "items", "settings"],
        run: function() {
            var t, e, i, s = this.settings.rtl ? 1 : -1, n = (this.width() / this.settings.items).toFixed(3), o = 0;
            for (this._coordinates = [],
            e = 0,
            i = this._clones.length + this._items.length; e < i; e++)
                t = this._mergers[this.relative(e)],
                t = this.settings.mergeFit && Math.min(t, this.settings.items) || t,
                o += (this.settings.autoWidth ? this._items[this.relative(e)].width() + this.settings.margin : n * t) * s,
                this._coordinates.push(o)
        }
    }, {
        filter: ["width", "items", "settings"],
        run: function() {
            var t, e, i = (this.width() / this.settings.items).toFixed(3), s = {
                width: Math.abs(this._coordinates[this._coordinates.length - 1]) + 2 * this.settings.stagePadding,
                "padding-left": this.settings.stagePadding || "",
                "padding-right": this.settings.stagePadding || ""
            };
            if (this.$stage.css(s),
            (s = {
                width: this.settings.autoWidth ? "auto" : i - this.settings.margin
            })[this.settings.rtl ? "margin-left" : "margin-right"] = this.settings.margin,
            !this.settings.autoWidth && 0 < l.grep(this._mergers, function(t) {
                return 1 < t
            }).length)
                for (t = 0,
                e = this._coordinates.length; t < e; t++)
                    s.width = Math.abs(this._coordinates[t]) - Math.abs(this._coordinates[t - 1] || 0) - this.settings.margin,
                    this.$stage.children().eq(t).css(s);
            else
                this.$stage.children().css(s)
        }
    }, {
        filter: ["width", "items", "settings"],
        run: function(t) {
            t.current && this.reset(this.$stage.children().index(t.current))
        }
    }, {
        filter: ["position"],
        run: function() {
            this.animate(this.coordinates(this._current))
        }
    }, {
        filter: ["width", "position", "items", "settings"],
        run: function() {
            for (var t, e, i = this.settings.rtl ? 1 : -1, s = 2 * this.settings.stagePadding, n = this.coordinates(this.current()) + s, o = n + this.width() * i, r = [], a = 0, l = this._coordinates.length; a < l; a++)
                t = this._coordinates[a - 1] || 0,
                e = Math.abs(this._coordinates[a]) + s * i,
                (this.op(t, "<=", n) && this.op(t, ">", o) || this.op(e, "<", n) && this.op(e, ">", o)) && r.push(a);
            this.$stage.children("." + this.settings.activeClass).removeClass(this.settings.activeClass),
            this.$stage.children(":eq(" + r.join("), :eq(") + ")").addClass(this.settings.activeClass),
            this.settings.center && (this.$stage.children("." + this.settings.centerClass).removeClass(this.settings.centerClass),
            this.$stage.children().eq(this.current()).addClass(this.settings.centerClass))
        }
    }],
    i.prototype.initialize = function() {
        if (this.trigger("initialize"),
        this.$element.addClass(this.settings.baseClass).addClass(this.settings.themeClass).toggleClass("owl-rtl", this.settings.rtl),
        this.browserSupport(),
        this.settings.autoWidth && !0 !== this.state.imagesLoaded) {
            var t = this.$element.find("img")
              , e = this.settings.nestedItemSelector ? "." + this.settings.nestedItemSelector : r
              , e = this.$element.children(e).width();
            if (t.length && e <= 0)
                return this.preloadAutoWidthImages(t),
                !1
        }
        this.$element.addClass("owl-loading"),
        this.$stage = l("<" + this.settings.stageElement + ' class="owl-stage"/>').wrap('<div class="owl-stage-outer">'),
        this.$element.append(this.$stage.parent()),
        this.replace(this.$element.children().not(this.$stage.parent())),
        this._width = this.$element.width(),
        this.refresh(),
        this.$element.removeClass("owl-loading").addClass("owl-loaded"),
        this.eventsCall(),
        this.internalEvents(),
        this.addTriggerableEvents(),
        this.trigger("initialized")
    }
    ,
    i.prototype.setup = function() {
        var e = this.viewport()
          , t = this.options.responsive
          , i = -1
          , s = null;
        t ? (l.each(t, function(t) {
            t <= e && i < t && (i = Number(t))
        }),
        delete (s = l.extend({}, this.options, t[i])).responsive,
        s.responsiveClass && this.$element.attr("class", function(t, e) {
            return e.replace(/\b owl-responsive-\S+/g, "")
        }).addClass("owl-responsive-" + i)) : s = l.extend({}, this.options),
        null !== this.settings && this._breakpoint === i || (this.trigger("change", {
            property: {
                name: "settings",
                value: s
            }
        }),
        this._breakpoint = i,
        this.settings = s,
        this.invalidate("settings"),
        this.trigger("changed", {
            property: {
                name: "settings",
                value: this.settings
            }
        }))
    }
    ,
    i.prototype.optionsLogic = function() {
        this.$element.toggleClass("owl-center", this.settings.center),
        this.settings.loop && this._items.length < this.settings.items && (this.settings.loop = !1),
        this.settings.autoWidth && (this.settings.stagePadding = !1,
        this.settings.merge = !1)
    }
    ,
    i.prototype.prepare = function(t) {
        var e = this.trigger("prepare", {
            content: t
        });
        return e.data || (e.data = l("<" + this.settings.itemElement + "/>").addClass(this.settings.itemClass).append(t)),
        this.trigger("prepared", {
            content: e.data
        }),
        e.data
    }
    ,
    i.prototype.update = function() {
        for (var t = 0, e = this._pipe.length, i = l.proxy(function(t) {
            return this[t]
        }, this._invalidated), s = {}; t < e; )
            (this._invalidated.all || 0 < l.grep(this._pipe[t].filter, i).length) && this._pipe[t].run(s),
            t++;
        this._invalidated = {}
    }
    ,
    i.prototype.width = function(t) {
        switch (t = t || i.Width.Default) {
        case i.Width.Inner:
        case i.Width.Outer:
            return this._width;
        default:
            return this._width - 2 * this.settings.stagePadding + this.settings.margin
        }
    }
    ,
    i.prototype.refresh = function() {
        if (0 === this._items.length)
            return !1;
        (new Date).getTime(),
        this.trigger("refresh"),
        this.setup(),
        this.optionsLogic(),
        this.$stage.addClass("owl-refresh"),
        this.update(),
        this.$stage.removeClass("owl-refresh"),
        this.state.orientation = h.orientation,
        this.watchVisibility(),
        this.trigger("refreshed")
    }
    ,
    i.prototype.eventsCall = function() {
        this.e._onDragStart = l.proxy(function(t) {
            this.onDragStart(t)
        }, this),
        this.e._onDragMove = l.proxy(function(t) {
            this.onDragMove(t)
        }, this),
        this.e._onDragEnd = l.proxy(function(t) {
            this.onDragEnd(t)
        }, this),
        this.e._onResize = l.proxy(function(t) {
            this.onResize(t)
        }, this),
        this.e._transitionEnd = l.proxy(function(t) {
            this.transitionEnd(t)
        }, this),
        this.e._preventClick = l.proxy(function(t) {
            this.preventClick(t)
        }, this)
    }
    ,
    i.prototype.onThrottledResize = function() {
        h.clearTimeout(this.resizeTimer),
        this.resizeTimer = h.setTimeout(this.e._onResize, this.settings.responsiveRefreshRate)
    }
    ,
    i.prototype.onResize = function() {
        return !!this._items.length && (this._width !== this.$element.width() && (!this.trigger("resize").isDefaultPrevented() && (this._width = this.$element.width(),
        this.invalidate("width"),
        this.refresh(),
        void this.trigger("resized"))))
    }
    ,
    i.prototype.eventsRouter = function(t) {
        var e = t.type;
        "mousedown" === e || "touchstart" === e ? this.onDragStart(t) : "mousemove" === e || "touchmove" === e ? this.onDragMove(t) : "mouseup" !== e && "touchend" !== e && "touchcancel" !== e || this.onDragEnd(t)
    }
    ,
    i.prototype.internalEvents = function() {
        "ontouchstart"in h || navigator.msMaxTouchPoints;
        var t = h.navigator.msPointerEnabled;
        this.settings.mouseDrag ? (this.$stage.on("mousedown", l.proxy(function(t) {
            this.eventsRouter(t)
        }, this)),
        this.$stage.on("dragstart", function() {
            return !1
        }),
        this.$stage.get(0).onselectstart = function() {
            return !1
        }
        ) : this.$element.addClass("owl-text-select-on"),
        this.settings.touchDrag && !t && this.$stage.on("touchstart touchcancel", l.proxy(function(t) {
            this.eventsRouter(t)
        }, this)),
        this.transitionEndVendor && this.on(this.$stage.get(0), this.transitionEndVendor, this.e._transitionEnd, !1),
        !1 !== this.settings.responsive && this.on(h, "resize", l.proxy(this.onThrottledResize, this))
    }
    ,
    i.prototype.onDragStart = function(t) {
        var e, i, s, t = t.originalEvent || t || h.event;
        if (3 === t.which || this.state.isTouch)
            return !1;
        if ("mousedown" === t.type && this.$stage.addClass("owl-grab"),
        this.trigger("drag"),
        this.drag.startTime = (new Date).getTime(),
        this.speed(0),
        this.state.isTouch = !0,
        this.state.isScrolling = !1,
        this.state.isSwiping = !1,
        this.drag.distance = 0,
        e = n(t).x,
        i = n(t).y,
        this.drag.offsetX = this.$stage.position().left,
        this.drag.offsetY = this.$stage.position().top,
        this.settings.rtl && (this.drag.offsetX = this.$stage.position().left + this.$stage.width() - this.width() + this.settings.margin),
        this.state.inMotion && this.support3d)
            s = this.getTransformProperty(),
            this.drag.offsetX = s,
            this.animate(s),
            this.state.inMotion = !0;
        else if (this.state.inMotion && !this.support3d)
            return this.state.inMotion = !1;
        this.drag.startX = e - this.drag.offsetX,
        this.drag.startY = i - this.drag.offsetY,
        this.drag.start = e - this.drag.startX,
        this.drag.targetEl = t.target || t.srcElement,
        this.drag.updatedX = this.drag.start,
        "IMG" !== this.drag.targetEl.tagName && "A" !== this.drag.targetEl.tagName || (this.drag.targetEl.draggable = !1),
        l(o).on("mousemove.owl.dragEvents mouseup.owl.dragEvents touchmove.owl.dragEvents touchend.owl.dragEvents", l.proxy(function(t) {
            this.eventsRouter(t)
        }, this))
    }
    ,
    i.prototype.onDragMove = function(t) {
        var e, i, s;
        this.state.isTouch && !this.state.isScrolling && (e = n(t = t.originalEvent || t || h.event).x,
        i = n(t).y,
        this.drag.currentX = e - this.drag.startX,
        this.drag.currentY = i - this.drag.startY,
        this.drag.distance = this.drag.currentX - this.drag.offsetX,
        this.drag.distance < 0 ? this.state.direction = this.settings.rtl ? "right" : "left" : 0 < this.drag.distance && (this.state.direction = this.settings.rtl ? "left" : "right"),
        this.settings.loop ? this.op(this.drag.currentX, ">", this.coordinates(this.minimum())) && "right" === this.state.direction ? this.drag.currentX -= (this.settings.center && this.coordinates(0)) - this.coordinates(this._items.length) : this.op(this.drag.currentX, "<", this.coordinates(this.maximum())) && "left" === this.state.direction && (this.drag.currentX += (this.settings.center && this.coordinates(0)) - this.coordinates(this._items.length)) : (e = this.coordinates(this.settings.rtl ? this.maximum() : this.minimum()),
        i = this.coordinates(this.settings.rtl ? this.minimum() : this.maximum()),
        s = this.settings.pullDrag ? this.drag.distance / 5 : 0,
        this.drag.currentX = Math.max(Math.min(this.drag.currentX, e + s), i + s)),
        (8 < this.drag.distance || this.drag.distance < -8) && (t.preventDefault !== r ? t.preventDefault() : t.returnValue = !1,
        this.state.isSwiping = !0),
        this.drag.updatedX = this.drag.currentX,
        (16 < this.drag.currentY || this.drag.currentY < -16) && !1 === this.state.isSwiping && (this.state.isScrolling = !0,
        this.drag.updatedX = this.drag.start),
        this.animate(this.drag.updatedX))
    }
    ,
    i.prototype.onDragEnd = function(t) {
        if (this.state.isTouch) {
            if ("mouseup" === t.type && this.$stage.removeClass("owl-grab"),
            this.trigger("dragged"),
            this.drag.targetEl.removeAttribute("draggable"),
            this.state.isTouch = !1,
            this.state.isScrolling = !1,
            this.state.isSwiping = !1,
            0 === this.drag.distance && !0 !== this.state.inMotion)
                return this.state.inMotion = !1;
            this.drag.endTime = (new Date).getTime(),
            t = this.drag.endTime - this.drag.startTime,
            (3 < Math.abs(this.drag.distance) || 300 < t) && this.removeClick(this.drag.targetEl),
            t = this.closest(this.drag.updatedX),
            this.speed(this.settings.dragEndSpeed || this.settings.smartSpeed),
            this.current(t),
            this.invalidate("position"),
            this.update(),
            this.settings.pullDrag || this.drag.updatedX !== this.coordinates(t) || this.transitionEnd(),
            this.drag.distance = 0,
            l(o).off(".owl.dragEvents")
        }
    }
    ,
    i.prototype.removeClick = function(t) {
        this.drag.targetEl = t,
        l(t).on("click.preventClick", this.e._preventClick),
        h.setTimeout(function() {
            l(t).off("click.preventClick")
        }, 300)
    }
    ,
    i.prototype.preventClick = function(t) {
        t.preventDefault ? t.preventDefault() : t.returnValue = !1,
        t.stopPropagation && t.stopPropagation(),
        l(t.target).off("click.preventClick")
    }
    ,
    i.prototype.getTransformProperty = function() {
        var t = h.getComputedStyle(this.$stage.get(0), null).getPropertyValue(this.vendorName + "transform");
        return !0 != (16 === (t = t.replace(/matrix(3d)?\(|\)/g, "").split(",")).length) ? t[4] : t[12]
    }
    ,
    i.prototype.closest = function(i) {
        var s = -1
          , n = this.width()
          , o = this.coordinates();
        return this.settings.freeDrag || l.each(o, l.proxy(function(t, e) {
            return e - 30 < i && i < e + 30 ? s = t : this.op(i, "<", e) && this.op(i, ">", o[t + 1] || e - n) && (s = "left" === this.state.direction ? t + 1 : t),
            -1 === s
        }, this)),
        this.settings.loop || (this.op(i, ">", o[this.minimum()]) ? s = i = this.minimum() : this.op(i, "<", o[this.maximum()]) && (s = i = this.maximum())),
        s
    }
    ,
    i.prototype.animate = function(t) {
        this.trigger("translate"),
        this.state.inMotion = 0 < this.speed(),
        this.support3d ? this.$stage.css({
            transform: "translate3d(" + t + "px,0px, 0px)",
            transition: this.speed() / 1e3 + "s"
        }) : this.state.isTouch ? this.$stage.css({
            left: t + "px"
        }) : this.$stage.animate({
            left: t
        }, this.speed() / 1e3, this.settings.fallbackEasing, l.proxy(function() {
            this.state.inMotion && this.transitionEnd()
        }, this))
    }
    ,
    i.prototype.current = function(t) {
        return t === r ? this._current : 0 === this._items.length ? r : (t = this.normalize(t),
        this._current !== t && ((e = this.trigger("change", {
            property: {
                name: "position",
                value: t
            }
        })).data !== r && (t = this.normalize(e.data)),
        this._current = t,
        this.invalidate("position"),
        this.trigger("changed", {
            property: {
                name: "position",
                value: this._current
            }
        })),
        this._current);
        var e
    }
    ,
    i.prototype.invalidate = function(t) {
        this._invalidated[t] = !0
    }
    ,
    i.prototype.reset = function(t) {
        (t = this.normalize(t)) !== r && (this._speed = 0,
        this._current = t,
        this.suppress(["translate", "translated"]),
        this.animate(this.coordinates(t)),
        this.release(["translate", "translated"]))
    }
    ,
    i.prototype.normalize = function(t, e) {
        var i = e ? this._items.length : this._items.length + this._clones.length;
        return !l.isNumeric(t) || i < 1 ? r : this._clones.length ? (t % i + i) % i : Math.max(this.minimum(e), Math.min(this.maximum(e), t))
    }
    ,
    i.prototype.relative = function(t) {
        return t = this.normalize(t),
        t -= this._clones.length / 2,
        this.normalize(t, !0)
    }
    ,
    i.prototype.maximum = function(t) {
        var e, i, s, n = 0, o = this.settings;
        if (t)
            return this._items.length - 1;
        if (!o.loop && o.center)
            e = this._items.length - 1;
        else if (o.loop || o.center)
            if (o.loop || o.center)
                e = this._items.length + o.items;
            else {
                if (!o.autoWidth && !o.merge)
                    throw "Can not detect maximum absolute position.";
                for (revert = o.rtl ? 1 : -1,
                i = this.$stage.width() - this.$element.width(); (s = this.coordinates(n)) && !(s * revert >= i); )
                    e = ++n
            }
        else
            e = this._items.length - o.items;
        return e
    }
    ,
    i.prototype.minimum = function(t) {
        return t ? 0 : this._clones.length / 2
    }
    ,
    i.prototype.items = function(t) {
        return t === r ? this._items.slice() : (t = this.normalize(t, !0),
        this._items[t])
    }
    ,
    i.prototype.mergers = function(t) {
        return t === r ? this._mergers.slice() : (t = this.normalize(t, !0),
        this._mergers[t])
    }
    ,
    i.prototype.clones = function(i) {
        function s(t) {
            return t % 2 == 0 ? n + t / 2 : e - (t + 1) / 2
        }
        var e = this._clones.length / 2
          , n = e + this._items.length;
        return i === r ? l.map(this._clones, function(t, e) {
            return s(e)
        }) : l.map(this._clones, function(t, e) {
            return t === i ? s(e) : null
        })
    }
    ,
    i.prototype.speed = function(t) {
        return t !== r && (this._speed = t),
        this._speed
    }
    ,
    i.prototype.coordinates = function(t) {
        var e = null;
        return t === r ? l.map(this._coordinates, l.proxy(function(t, e) {
            return this.coordinates(e)
        }, this)) : (this.settings.center ? (e = this._coordinates[t],
        e += (this.width() - e + (this._coordinates[t - 1] || 0)) / 2 * (this.settings.rtl ? -1 : 1)) : e = this._coordinates[t - 1] || 0,
        e)
    }
    ,
    i.prototype.duration = function(t, e, i) {
        return Math.min(Math.max(Math.abs(e - t), 1), 6) * Math.abs(i || this.settings.smartSpeed)
    }
    ,
    i.prototype.to = function(t, e) {
        var i, s, n, o, r, a;
        this.settings.loop ? (i = t - this.relative(this.current()),
        s = this.current(),
        r = (n = this.current()) - (o = this.current() + i) < 0,
        a = this._clones.length + this._items.length,
        o < this.settings.items && !1 == r ? (s = n + this._items.length,
        this.reset(s)) : o >= a - this.settings.items && !0 == r && (s = n - this._items.length,
        this.reset(s)),
        h.clearTimeout(this.e._goToLoop),
        this.e._goToLoop = h.setTimeout(l.proxy(function() {
            this.speed(this.duration(this.current(), s + i, e)),
            this.current(s + i),
            this.update()
        }, this), 30)) : (this.speed(this.duration(this.current(), t, e)),
        this.current(t),
        this.update())
    }
    ,
    i.prototype.next = function(t) {
        t = t || !1,
        this.to(this.relative(this.current()) + 1, t)
    }
    ,
    i.prototype.prev = function(t) {
        t = t || !1,
        this.to(this.relative(this.current()) - 1, t)
    }
    ,
    i.prototype.transitionEnd = function(t) {
        return (t === r || (t.stopPropagation(),
        (t.target || t.srcElement || t.originalTarget) === this.$stage.get(0))) && (this.state.inMotion = !1,
        void this.trigger("translated"))
    }
    ,
    i.prototype.viewport = function() {
        var t;
        if (this.options.responsiveBaseElement !== h)
            t = l(this.options.responsiveBaseElement).width();
        else if (h.innerWidth)
            t = h.innerWidth;
        else {
            if (!o.documentElement || !o.documentElement.clientWidth)
                throw "Can not detect viewport width.";
            t = o.documentElement.clientWidth
        }
        return t
    }
    ,
    i.prototype.replace = function(t) {
        this.$stage.empty(),
        this._items = [],
        t = t && (t instanceof jQuery ? t : l(t)),
        (t = this.settings.nestedItemSelector ? t.find("." + this.settings.nestedItemSelector) : t).filter(function() {
            return 1 === this.nodeType
        }).each(l.proxy(function(t, e) {
            e = this.prepare(e),
            this.$stage.append(e),
            this._items.push(e),
            this._mergers.push(+e.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)
        }, this)),
        this.reset(l.isNumeric(this.settings.startPosition) ? this.settings.startPosition : 0),
        this.invalidate("items")
    }
    ,
    i.prototype.add = function(t, e) {
        e = e === r ? this._items.length : this.normalize(e, !0),
        this.trigger("add", {
            content: t,
            position: e
        }),
        0 === this._items.length || e === this._items.length ? (this.$stage.append(t),
        this._items.push(t),
        this._mergers.push(+t.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)) : (this._items[e].before(t),
        this._items.splice(e, 0, t),
        this._mergers.splice(e, 0, +t.find("[data-merge]").addBack("[data-merge]").attr("data-merge") || 1)),
        this.invalidate("items"),
        this.trigger("added", {
            content: t,
            position: e
        })
    }
    ,
    i.prototype.remove = function(t) {
        (t = this.normalize(t, !0)) !== r && (this.trigger("remove", {
            content: this._items[t],
            position: t
        }),
        this._items[t].remove(),
        this._items.splice(t, 1),
        this._mergers.splice(t, 1),
        this.invalidate("items"),
        this.trigger("removed", {
            content: null,
            position: t
        }))
    }
    ,
    i.prototype.addTriggerableEvents = function() {
        var i = l.proxy(function(e, i) {
            return l.proxy(function(t) {
                t.relatedTarget !== this && (this.suppress([i]),
                e.apply(this, [].slice.call(arguments, 1)),
                this.release([i]))
            }, this)
        }, this);
        l.each({
            next: this.next,
            prev: this.prev,
            to: this.to,
            destroy: this.destroy,
            refresh: this.refresh,
            replace: this.replace,
            add: this.add,
            remove: this.remove
        }, l.proxy(function(t, e) {
            this.$element.on(t + ".owl.carousel", i(e, t + ".owl.carousel"))
        }, this))
    }
    ,
    i.prototype.watchVisibility = function() {
        function t(t) {
            return 0 < t.offsetWidth && 0 < t.offsetHeight
        }
        t(this.$element.get(0)) || (this.$element.addClass("owl-hidden"),
        h.clearInterval(this.e._checkVisibile),
        this.e._checkVisibile = h.setInterval(l.proxy(function() {
            t(this.$element.get(0)) && (this.$element.removeClass("owl-hidden"),
            this.refresh(),
            h.clearInterval(this.e._checkVisibile))
        }, this), 500))
    }
    ,
    i.prototype.preloadAutoWidthImages = function(i) {
        var s, n, o = 0, r = this;
        i.each(function(t, e) {
            s = l(e),
            (n = new Image).onload = function() {
                o++,
                s.attr("src", n.src),
                s.css("opacity", 1),
                o >= i.length && (r.state.imagesLoaded = !0,
                r.initialize())
            }
            ,
            n.src = s.attr("src") || s.attr("data-src") || s.attr("data-src-retina")
        })
    }
    ,
    i.prototype.destroy = function() {
        for (var t in this.$element.hasClass(this.settings.themeClass) && this.$element.removeClass(this.settings.themeClass),
        !1 !== this.settings.responsive && l(h).off("resize.owl.carousel"),
        this.transitionEndVendor && this.off(this.$stage.get(0), this.transitionEndVendor, this.e._transitionEnd),
        this._plugins)
            this._plugins[t].destroy();
        (this.settings.mouseDrag || this.settings.touchDrag) && (this.$stage.off("mousedown touchstart touchcancel"),
        l(o).off(".owl.dragEvents"),
        this.$stage.get(0).onselectstart = function() {}
        ,
        this.$stage.off("dragstart", function() {
            return !1
        })),
        this.$element.off(".owl"),
        this.$stage.children(".cloned").remove(),
        this.e = null,
        this.$element.removeData("owlCarousel"),
        this.$stage.children().contents().unwrap(),
        this.$stage.children().unwrap(),
        this.$stage.unwrap()
    }
    ,
    i.prototype.op = function(t, e, i) {
        var s = this.settings.rtl;
        switch (e) {
        case "<":
            return s ? i < t : t < i;
        case ">":
            return s ? t < i : i < t;
        case ">=":
            return s ? t <= i : i <= t;
        case "<=":
            return s ? i <= t : t <= i
        }
    }
    ,
    i.prototype.on = function(t, e, i, s) {
        t.addEventListener ? t.addEventListener(e, i, s) : t.attachEvent && t.attachEvent("on" + e, i)
    }
    ,
    i.prototype.off = function(t, e, i, s) {
        t.removeEventListener ? t.removeEventListener(e, i, s) : t.detachEvent && t.detachEvent("on" + e, i)
    }
    ,
    i.prototype.trigger = function(t, e, i) {
        var s = {
            item: {
                count: this._items.length,
                index: this.current()
            }
        }
          , n = l.camelCase(l.grep(["on", t, i], function(t) {
            return t
        }).join("-").toLowerCase())
          , o = l.Event([t, "owl", i || "carousel"].join(".").toLowerCase(), l.extend({
            relatedTarget: this
        }, s, e));
        return this._supress[t] || (l.each(this._plugins, function(t, e) {
            e.onTrigger && e.onTrigger(o)
        }),
        this.$element.trigger(o),
        this.settings && "function" == typeof this.settings[n] && this.settings[n].apply(this, o)),
        o
    }
    ,
    i.prototype.suppress = function(t) {
        l.each(t, l.proxy(function(t, e) {
            this._supress[e] = !0
        }, this))
    }
    ,
    i.prototype.release = function(t) {
        l.each(t, l.proxy(function(t, e) {
            delete this._supress[e]
        }, this))
    }
    ,
    i.prototype.browserSupport = function() {
        this.support3d = t(["perspective", "webkitPerspective", "MozPerspective", "OPerspective", "MsPerspective"])[0],
        this.support3d && (this.transformVendor = t(["transform", "WebkitTransform", "MozTransform", "OTransform", "msTransform"])[0],
        this.transitionEndVendor = ["transitionend", "webkitTransitionEnd", "transitionend", "oTransitionEnd"][t(["transition", "WebkitTransition", "MozTransition", "OTransition"])[1]],
        this.vendorName = this.transformVendor.replace(/Transform/i, ""),
        this.vendorName = "" !== this.vendorName ? "-" + this.vendorName.toLowerCase() + "-" : ""),
        this.state.orientation = h.orientation
    }
    ,
    l.fn.owlCarousel = function(t) {
        return this.each(function() {
            l(this).data("owlCarousel") || l(this).data("owlCarousel", new i(this,t))
        })
    }
    ,
    l.fn.owlCarousel.Constructor = i
}(window.Zepto || window.jQuery, window, document),
function(a, n) {
    function e(t) {
        this._core = t,
        this._loaded = [],
        this._handlers = {
            "initialized.owl.carousel change.owl.carousel": a.proxy(function(t) {
                if (t.namespace && this._core.settings && this._core.settings.lazyLoad && (t.property && "position" == t.property.name || "initialized" == t.type))
                    for (var e = this._core.settings, i = e.center && Math.ceil(e.items / 2) || e.items, s = e.center && -1 * i || 0, n = (t.property && t.property.value || this._core.current()) + s, o = this._core.clones().length, r = a.proxy(function(t, e) {
                        this.load(e)
                    }, this); s++ < i; )
                        this.load(o / 2 + this._core.relative(n)),
                        o && a.each(this._core.clones(this._core.relative(n++)), r)
            }, this)
        },
        this._core.options = a.extend({}, e.Defaults, this._core.options),
        this._core.$element.on(this._handlers)
    }
    e.Defaults = {
        lazyLoad: !1
    },
    e.prototype.load = function(t) {
        var t = this._core.$stage.children().eq(t)
          , e = t && t.find(".owl-lazy");
        !e || -1 < a.inArray(t.get(0), this._loaded) || (e.each(a.proxy(function(t, e) {
            var i = a(e)
              , s = 1 < n.devicePixelRatio && i.attr("data-src-retina") || i.attr("data-src");
            this._core.trigger("load", {
                element: i,
                url: s
            }, "lazy"),
            i.is("img") ? i.one("load.owl.lazy", a.proxy(function() {
                i.css("opacity", 1),
                this._core.trigger("loaded", {
                    element: i,
                    url: s
                }, "lazy")
            }, this)).attr("src", s) : ((e = new Image).onload = a.proxy(function() {
                i.css({
                    "background-image": "url(" + s + ")",
                    opacity: "1"
                }),
                this._core.trigger("loaded", {
                    element: i,
                    url: s
                }, "lazy")
            }, this),
            e.src = s)
        }, this)),
        this._loaded.push(t.get(0)))
    }
    ,
    e.prototype.destroy = function() {
        var t, e;
        for (t in this.handlers)
            this._core.$element.off(t, this.handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    a.fn.owlCarousel.Constructor.Plugins.Lazy = e
}(window.Zepto || window.jQuery, window, document),
function(e) {
    function i(t) {
        this._core = t,
        this._handlers = {
            "initialized.owl.carousel": e.proxy(function() {
                this._core.settings.autoHeight && this.update()
            }, this),
            "changed.owl.carousel": e.proxy(function(t) {
                this._core.settings.autoHeight && "position" == t.property.name && this.update()
            }, this),
            "loaded.owl.lazy": e.proxy(function(t) {
                this._core.settings.autoHeight && t.element.closest("." + this._core.settings.itemClass) === this._core.$stage.children().eq(this._core.current()) && this.update()
            }, this)
        },
        this._core.options = e.extend({}, i.Defaults, this._core.options),
        this._core.$element.on(this._handlers)
    }
    i.Defaults = {
        autoHeight: !1,
        autoHeightClass: "owl-height"
    },
    i.prototype.update = function() {
        this._core.$stage.parent().height(this._core.$stage.children().eq(this._core.current()).height()).addClass(this._core.settings.autoHeightClass)
    }
    ,
    i.prototype.destroy = function() {
        var t, e;
        for (t in this._handlers)
            this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    e.fn.owlCarousel.Constructor.Plugins.AutoHeight = i
}(window.Zepto || window.jQuery, (window,
document)),
function(d, e, i) {
    function s(t) {
        this._core = t,
        this._videos = {},
        this._playing = null,
        this._fullscreen = !1,
        this._handlers = {
            "resize.owl.carousel": d.proxy(function(t) {
                this._core.settings.video && !this.isInFullScreen() && t.preventDefault()
            }, this),
            "refresh.owl.carousel changed.owl.carousel": d.proxy(function() {
                this._playing && this.stop()
            }, this),
            "prepared.owl.carousel": d.proxy(function(t) {
                var e = d(t.content).find(".owl-video");
                e.length && (e.css("display", "none"),
                this.fetch(e, d(t.content)))
            }, this)
        },
        this._core.options = d.extend({}, s.Defaults, this._core.options),
        this._core.$element.on(this._handlers),
        this._core.$element.on("click.owl.video", ".owl-video-play-icon", d.proxy(function(t) {
            this.play(t)
        }, this))
    }
    s.Defaults = {
        video: !1,
        videoHeight: !1,
        videoWidth: !1
    },
    s.prototype.fetch = function(t, e) {
        var i = t.attr("data-vimeo-id") ? "vimeo" : "youtube"
          , s = t.attr("data-vimeo-id") || t.attr("data-youtube-id")
          , n = t.attr("data-width") || this._core.settings.videoWidth
          , o = t.attr("data-height") || this._core.settings.videoHeight
          , r = t.attr("href");
        if (!r)
            throw new Error("Missing video URL.");
        if (-1 < (s = r.match(/(http:|https:|)\/\/(player.|www.)?(vimeo\.com|youtu(be\.com|\.be|be\.googleapis\.com))\/(video\/|embed\/|watch\?v=|v\/)?([A-Za-z0-9._%-]*)(\&\S+)?/))[3].indexOf("youtu"))
            i = "youtube";
        else {
            if (!(-1 < s[3].indexOf("vimeo")))
                throw new Error("Video URL not supported.");
            i = "vimeo"
        }
        s = s[6],
        this._videos[r] = {
            type: i,
            id: s,
            width: n,
            height: o
        },
        e.attr("data-video", r),
        this.thumbnail(t, this._videos[r])
    }
    ,
    s.prototype.thumbnail = function(e, t) {
        function i(t) {
            s = h.lazyLoad ? '<div class="owl-video-tn ' + l + '" ' + a + '="' + t + '"></div>' : '<div class="owl-video-tn" style="opacity:1;background-image:url(' + t + ')"></div>',
            e.after(s),
            e.after('<div class="owl-video-play-icon"></div>')
        }
        var s, n, o = t.width && t.height ? 'style="width:' + t.width + "px;height:" + t.height + 'px;"' : "", r = e.find("img"), a = "src", l = "", h = this._core.settings;
        return e.wrap('<div class="owl-video-wrapper"' + o + "></div>"),
        this._core.settings.lazyLoad && (a = "data-src",
        l = "owl-lazy"),
        r.length ? (i(r.attr(a)),
        r.remove(),
        !1) : void ("youtube" === t.type ? (n = "http://img.youtube.com/vi/" + t.id + "/hqdefault.jpg",
        i(n)) : "vimeo" === t.type && d.ajax({
            type: "GET",
            url: "http://vimeo.com/api/v2/video/" + t.id + ".json",
            jsonp: "callback",
            dataType: "jsonp",
            success: function(t) {
                n = t[0].thumbnail_large,
                i(n)
            }
        }))
    }
    ,
    s.prototype.stop = function() {
        this._core.trigger("stop", null, "video"),
        this._playing.find(".owl-video-frame").remove(),
        this._playing.removeClass("owl-video-playing"),
        this._playing = null
    }
    ,
    s.prototype.play = function(t) {
        this._core.trigger("play", null, "video"),
        this._playing && this.stop();
        var e, t = d(t.target || t.srcElement), i = t.closest("." + this._core.settings.itemClass), s = this._videos[i.attr("data-video")], n = s.width || "100%", o = s.height || this._core.$stage.height();
        "youtube" === s.type ? e = '<iframe width="' + n + '" height="' + o + '" src="http://www.youtube.com/embed/' + s.id + "?autoplay=1&v=" + s.id + '" frameborder="0" allowfullscreen></iframe>' : "vimeo" === s.type && (e = '<iframe src="http://player.vimeo.com/video/' + s.id + '?autoplay=1" width="' + n + '" height="' + o + '" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>'),
        i.addClass("owl-video-playing"),
        this._playing = i,
        s = d('<div style="height:' + o + "px; width:" + n + 'px" class="owl-video-frame">' + e + "</div>"),
        t.after(s)
    }
    ,
    s.prototype.isInFullScreen = function() {
        var t = i.fullscreenElement || i.mozFullScreenElement || i.webkitFullscreenElement;
        return t && d(t).parent().hasClass("owl-video-frame") && (this._core.speed(0),
        this._fullscreen = !0),
        !(t && this._fullscreen && this._playing) && (this._fullscreen ? this._fullscreen = !1 : !this._playing || this._core.state.orientation === e.orientation || (this._core.state.orientation = e.orientation,
        !1))
    }
    ,
    s.prototype.destroy = function() {
        var t, e;
        for (t in this._core.$element.off("click.owl.video"),
        this._handlers)
            this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    d.fn.owlCarousel.Constructor.Plugins.Video = s
}(window.Zepto || window.jQuery, window, document),
function(r) {
    function e(t) {
        this.core = t,
        this.core.options = r.extend({}, e.Defaults, this.core.options),
        this.swapping = !0,
        this.previous = void 0,
        this.next = void 0,
        this.handlers = {
            "change.owl.carousel": r.proxy(function(t) {
                "position" == t.property.name && (this.previous = this.core.current(),
                this.next = t.property.value)
            }, this),
            "drag.owl.carousel dragged.owl.carousel translated.owl.carousel": r.proxy(function(t) {
                this.swapping = "translated" == t.type
            }, this),
            "translate.owl.carousel": r.proxy(function() {
                this.swapping && (this.core.options.animateOut || this.core.options.animateIn) && this.swap()
            }, this)
        },
        this.core.$element.on(this.handlers)
    }
    e.Defaults = {
        animateOut: !1,
        animateIn: !1
    },
    e.prototype.swap = function() {
        var t, e, i, s, n, o;
        1 === this.core.settings.items && this.core.support3d && (this.core.speed(0),
        e = r.proxy(this.clear, this),
        i = this.core.$stage.children().eq(this.previous),
        s = this.core.$stage.children().eq(this.next),
        n = this.core.settings.animateIn,
        o = this.core.settings.animateOut,
        this.core.current() !== this.previous && (o && (t = this.core.coordinates(this.previous) - this.core.coordinates(this.next),
        i.css({
            left: t + "px"
        }).addClass("animated owl-animated-out").addClass(o).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", e)),
        n && s.addClass("animated owl-animated-in").addClass(n).one("webkitAnimationEnd mozAnimationEnd MSAnimationEnd oanimationend animationend", e)))
    }
    ,
    e.prototype.clear = function(t) {
        r(t.target).css({
            left: ""
        }).removeClass("animated owl-animated-out owl-animated-in").removeClass(this.core.settings.animateIn).removeClass(this.core.settings.animateOut),
        this.core.transitionEnd()
    }
    ,
    e.prototype.destroy = function() {
        var t, e;
        for (t in this.handlers)
            this.core.$element.off(t, this.handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    r.fn.owlCarousel.Constructor.Plugins.Animate = e
}(window.Zepto || window.jQuery, (window,
document)),
function(e, i, t) {
    function s(t) {
        this.core = t,
        this.core.options = e.extend({}, s.Defaults, this.core.options),
        this.handlers = {
            "translated.owl.carousel refreshed.owl.carousel": e.proxy(function() {
                this.autoplay()
            }, this),
            "play.owl.autoplay": e.proxy(function(t, e, i) {
                this.play(e, i)
            }, this),
            "stop.owl.autoplay": e.proxy(function() {
                this.stop()
            }, this),
            "mouseover.owl.autoplay": e.proxy(function() {
                this.core.settings.autoplayHoverPause && this.pause()
            }, this),
            "mouseleave.owl.autoplay": e.proxy(function() {
                this.core.settings.autoplayHoverPause && this.autoplay()
            }, this)
        },
        this.core.$element.on(this.handlers)
    }
    s.Defaults = {
        autoplay: !1,
        autoplayTimeout: 5e3,
        autoplayHoverPause: !1,
        autoplaySpeed: !1
    },
    s.prototype.autoplay = function() {
        this.core.settings.autoplay && !this.core.state.videoPlay ? (i.clearInterval(this.interval),
        this.interval = i.setInterval(e.proxy(function() {
            this.play()
        }, this), this.core.settings.autoplayTimeout)) : i.clearInterval(this.interval)
    }
    ,
    s.prototype.play = function() {
        return !0 === t.hidden || this.core.state.isTouch || this.core.state.isScrolling || this.core.state.isSwiping || this.core.state.inMotion ? void 0 : !1 === this.core.settings.autoplay ? void i.clearInterval(this.interval) : void this.core.next(this.core.settings.autoplaySpeed)
    }
    ,
    s.prototype.stop = function() {
        i.clearInterval(this.interval)
    }
    ,
    s.prototype.pause = function() {
        i.clearInterval(this.interval)
    }
    ,
    s.prototype.destroy = function() {
        var t, e;
        for (t in i.clearInterval(this.interval),
        this.handlers)
            this.core.$element.off(t, this.handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    e.fn.owlCarousel.Constructor.Plugins.autoplay = s
}(window.Zepto || window.jQuery, window, document),
function(n) {
    "use strict";
    function e(t) {
        this._core = t,
        this._initialized = !1,
        this._pages = [],
        this._controls = {},
        this._templates = [],
        this.$element = this._core.$element,
        this._overrides = {
            next: this._core.next,
            prev: this._core.prev,
            to: this._core.to
        },
        this._handlers = {
            "prepared.owl.carousel": n.proxy(function(t) {
                this._core.settings.dotsData && this._templates.push(n(t.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot"))
            }, this),
            "add.owl.carousel": n.proxy(function(t) {
                this._core.settings.dotsData && this._templates.splice(t.position, 0, n(t.content).find("[data-dot]").addBack("[data-dot]").attr("data-dot"))
            }, this),
            "remove.owl.carousel prepared.owl.carousel": n.proxy(function(t) {
                this._core.settings.dotsData && this._templates.splice(t.position, 1)
            }, this),
            "change.owl.carousel": n.proxy(function(t) {
                var e, i, s;
                "position" != t.property.name || this._core.state.revert || this._core.settings.loop || !this._core.settings.navRewind || (e = this._core.current(),
                i = this._core.maximum(),
                s = this._core.minimum(),
                t.data = t.property.value > i ? i <= e ? s : i : t.property.value < s ? i : t.property.value)
            }, this),
            "changed.owl.carousel": n.proxy(function(t) {
                "position" == t.property.name && this.draw()
            }, this),
            "refreshed.owl.carousel": n.proxy(function() {
                this._initialized || (this.initialize(),
                this._initialized = !0),
                this._core.trigger("refresh", null, "navigation"),
                this.update(),
                this.draw(),
                this._core.trigger("refreshed", null, "navigation")
            }, this)
        },
        this._core.options = n.extend({}, e.Defaults, this._core.options),
        this.$element.on(this._handlers)
    }
    e.Defaults = {
        nav: !1,
        navRewind: !0,
        navText: ["prev", "next"],
        navSpeed: !1,
        navElement: "div",
        navContainer: !1,
        navContainerClass: "owl-nav",
        navClass: ["owl-prev", "owl-next"],
        slideBy: 1,
        dotClass: "owl-dot",
        dotsClass: "owl-dots",
        dots: !0,
        dotsEach: !1,
        dotData: !1,
        dotsSpeed: !1,
        dotsContainer: !1,
        controlsClass: "owl-controls"
    },
    e.prototype.initialize = function() {
        var t, e, i = this._core.settings;
        for (e in i.dotsData || (this._templates = [n("<div>").addClass(i.dotClass).append(n("<span>")).prop("outerHTML")]),
        i.navContainer && i.dotsContainer || (this._controls.$container = n("<div>").addClass(i.controlsClass).appendTo(this.$element)),
        this._controls.$indicators = i.dotsContainer ? n(i.dotsContainer) : n("<div>").hide().addClass(i.dotsClass).appendTo(this._controls.$container),
        this._controls.$indicators.on("click", "div", n.proxy(function(t) {
            var e = (n(t.target).parent().is(this._controls.$indicators) ? n(t.target) : n(t.target).parent()).index();
            t.preventDefault(),
            this.to(e, i.dotsSpeed)
        }, this)),
        t = i.navContainer ? n(i.navContainer) : n("<div>").addClass(i.navContainerClass).prependTo(this._controls.$container),
        this._controls.$next = n("<" + i.navElement + ">"),
        this._controls.$previous = this._controls.$next.clone(),
        this._controls.$previous.addClass(i.navClass[0]).html(i.navText[0]).hide().prependTo(t).on("click", n.proxy(function() {
            this.prev(i.navSpeed)
        }, this)),
        this._controls.$next.addClass(i.navClass[1]).html(i.navText[1]).hide().appendTo(t).on("click", n.proxy(function() {
            this.next(i.navSpeed)
        }, this)),
        this._overrides)
            this._core[e] = n.proxy(this[e], this)
    }
    ,
    e.prototype.destroy = function() {
        var t, e, i, s;
        for (t in this._handlers)
            this.$element.off(t, this._handlers[t]);
        for (e in this._controls)
            this._controls[e].remove();
        for (s in this.overides)
            this._core[s] = this._overrides[s];
        for (i in Object.getOwnPropertyNames(this))
            "function" != typeof this[i] && (this[i] = null)
    }
    ,
    e.prototype.update = function() {
        var t, e, i = this._core.settings, s = this._core.clones().length / 2, n = s + this._core.items().length, o = i.center || i.autoWidth || i.dotData ? 1 : i.dotsEach || i.items;
        if ("page" !== i.slideBy && (i.slideBy = Math.min(i.slideBy, i.items)),
        i.dots || "page" == i.slideBy)
            for (this._pages = [],
            t = s,
            e = 0; t < n; t++)
                (o <= e || 0 === e) && (this._pages.push({
                    start: t - s,
                    end: t - s + o - 1
                }),
                e = 0,
                0),
                e += this._core.mergers(this._core.relative(t))
    }
    ,
    e.prototype.draw = function() {
        var t, e = "", i = this._core.settings, s = (this._core.$stage.children(),
        this._core.relative(this._core.current()));
        if (!i.nav || i.loop || i.navRewind || (this._controls.$previous.toggleClass("disabled", s <= 0),
        this._controls.$next.toggleClass("disabled", s >= this._core.maximum())),
        this._controls.$previous.toggle(i.nav),
        this._controls.$next.toggle(i.nav),
        i.dots) {
            if (s = this._pages.length - this._controls.$indicators.children().length,
            i.dotData && 0 != s) {
                for (t = 0; t < this._controls.$indicators.children().length; t++)
                    e += this._templates[this._core.relative(t)];
                this._controls.$indicators.html(e)
            } else
                0 < s ? (e = new Array(1 + s).join(this._templates[0]),
                this._controls.$indicators.append(e)) : s < 0 && this._controls.$indicators.children().slice(s).remove();
            this._controls.$indicators.find(".active").removeClass("active"),
            this._controls.$indicators.children().eq(n.inArray(this.current(), this._pages)).addClass("active")
        }
        this._controls.$indicators.toggle(i.dots)
    }
    ,
    e.prototype.onTrigger = function(t) {
        var e = this._core.settings;
        t.page = {
            index: n.inArray(this.current(), this._pages),
            count: this._pages.length,
            size: e && (e.center || e.autoWidth || e.dotData ? 1 : e.dotsEach || e.items)
        }
    }
    ,
    e.prototype.current = function() {
        var e = this._core.relative(this._core.current());
        return n.grep(this._pages, function(t) {
            return t.start <= e && t.end >= e
        }).pop()
    }
    ,
    e.prototype.getPosition = function(t) {
        var e, i, s = this._core.settings;
        return "page" == s.slideBy ? (e = n.inArray(this.current(), this._pages),
        i = this._pages.length,
        t ? ++e : --e,
        e = this._pages[(e % i + i) % i].start) : (e = this._core.relative(this._core.current()),
        i = this._core.items().length,
        t ? e += s.slideBy : e -= s.slideBy),
        e
    }
    ,
    e.prototype.next = function(t) {
        n.proxy(this._overrides.to, this._core)(this.getPosition(!0), t)
    }
    ,
    e.prototype.prev = function(t) {
        n.proxy(this._overrides.to, this._core)(this.getPosition(!1), t)
    }
    ,
    e.prototype.to = function(t, e, i) {
        i ? n.proxy(this._overrides.to, this._core)(t, e) : (i = this._pages.length,
        n.proxy(this._overrides.to, this._core)(this._pages[(t % i + i) % i].start, e))
    }
    ,
    n.fn.owlCarousel.Constructor.Plugins.Navigation = e
}(window.Zepto || window.jQuery, (window,
document)),
function(i, s) {
    "use strict";
    function e(t) {
        this._core = t,
        this._hashes = {},
        this.$element = this._core.$element,
        this._handlers = {
            "initialized.owl.carousel": i.proxy(function() {
                "URLHash" == this._core.settings.startPosition && i(s).trigger("hashchange.owl.navigation")
            }, this),
            "prepared.owl.carousel": i.proxy(function(t) {
                var e = i(t.content).find("[data-hash]").addBack("[data-hash]").attr("data-hash");
                this._hashes[e] = t.content
            }, this)
        },
        this._core.options = i.extend({}, e.Defaults, this._core.options),
        this.$element.on(this._handlers),
        i(s).on("hashchange.owl.navigation", i.proxy(function() {
            var t = s.location.hash.substring(1)
              , e = this._core.$stage.children()
              , e = this._hashes[t] && e.index(this._hashes[t]) || 0;
            return !!t && void this._core.to(e, !1, !0)
        }, this))
    }
    e.Defaults = {
        URLhashListener: !1
    },
    e.prototype.destroy = function() {
        var t, e;
        for (t in i(s).off("hashchange.owl.navigation"),
        this._handlers)
            this._core.$element.off(t, this._handlers[t]);
        for (e in Object.getOwnPropertyNames(this))
            "function" != typeof this[e] && (this[e] = null)
    }
    ,
    i.fn.owlCarousel.Constructor.Plugins.Hash = e
}(window.Zepto || window.jQuery, window, document),
function($) {
    "use strict";
    function n() {
        var t, e, i, s, n, o;
        !b && document.body && (b = !0,
        t = document.body,
        e = document.documentElement,
        i = window.innerHeight,
        o = t.scrollHeight,
        C = 0 <= document.compatMode.indexOf("CSS") ? e : t,
        v = t,
        w.keyboardSupport && u("keydown", A),
        top != self ? _ = !0 : i < o && (t.offsetHeight <= i || e.offsetHeight <= i) && ((s = document.createElement("div")).style.cssText = "position:absolute; z-index:-10000; top:0; left:0; right:0; height:" + C.scrollHeight + "px",
        document.body.appendChild(s),
        o = function() {
            n = n || setTimeout(function() {
                y || (s.style.height = "0",
                s.style.height = C.scrollHeight + "px",
                n = null)
            }, 500)
        }
        ,
        setTimeout(o, 10),
        new N(o).observe(t, {
            attributes: !0,
            childList: !0,
            characterData: !1
        }),
        C.offsetHeight <= i && ((o = document.createElement("div")).style.clear = "both",
        t.appendChild(o))),
        w.fixedBackground || y || (t.style.backgroundAttachment = "scroll",
        e.style.backgroundAttachment = "scroll"))
    }
    function h(h, d, c) {
        var t, e, u, p;
        t = 0 < (t = d) ? 1 : -1,
        e = 0 < (e = c) ? 1 : -1,
        i.x === t && i.y === e || (i.x = t,
        i.y = e,
        E = [],
        s = 0),
        1 != w.accelerationMax && ((t = Date.now() - s) < w.accelerationDelta && (1 < (e = (1 + 50 / t) / 2) && (e = Math.min(e, w.accelerationMax),
        d *= e,
        c *= e)),
        s = Date.now()),
        E.push({
            x: d,
            y: c,
            lastX: d < 0 ? .99 : -.99,
            lastY: c < 0 ? .99 : -.99,
            start: Date.now()
        }),
        S || (u = h === document.body,
        M(p = function(t) {
            for (var e = Date.now(), i = 0, s = 0, n = 0; n < E.length; n++) {
                var o = E[n]
                  , r = e - o.start
                  , a = r >= w.animationTime
                  , r = a ? 1 : r / w.animationTime
                  , l = (w.pulseAlgorithm && (r = function(t) {
                    if (1 <= t)
                        return 1;
                    if (t <= 0)
                        return 0;
                    1 == w.pulseNormalize && (w.pulseNormalize /= g(1));
                    return g(t)
                }(r)),
                o.x * r - o.lastX >> 0)
                  , r = o.y * r - o.lastY >> 0;
                i += l,
                s += r,
                o.lastX += l,
                o.lastY += r,
                a && (E.splice(n, 1),
                n--)
            }
            u ? window.scrollBy(i, s) : (i && (h.scrollLeft += i),
            s && (h.scrollTop += s)),
            (E = d || c ? E : []).length ? M(p, h, 1e3 / w.frameRate + 1) : S = !1
        }
        , h, 0),
        S = !0)
    }
    function L(t) {
        b || n();
        var e, i = t.target, s = c(i);
        return !(s && !t.defaultPrevented && !t.ctrlKey) || (!!(p(v, "embed") || p(i, "embed") && /\.pdf/i.test(i.src) || p(v, "object")) || (i = -t.wheelDeltaX || t.deltaX || 0,
        e = -t.wheelDeltaY || t.deltaY || 0,
        k && (t.wheelDeltaX && f(t.wheelDeltaX, 120) && (i = t.wheelDeltaX / Math.abs(t.wheelDeltaX) * -120),
        t.wheelDeltaY && f(t.wheelDeltaY, 120) && (e = t.wheelDeltaY / Math.abs(t.wheelDeltaY) * -120)),
        i || e || (e = -t.wheelDelta || 0),
        1 === t.deltaMode && (i *= 40,
        e *= 40),
        !(w.touchpadSupport || !function(t) {
            if (t)
                return x.length || (x = [t, t, t]),
                t = Math.abs(t),
                x.push(t),
                x.shift(),
                clearTimeout(F),
                F = setTimeout(function() {
                    window.localStorage && (localStorage.SS_deltaBuffer = x.join(","))
                }, 1e3),
                !m(120) && !m(100)
        }(e)) || (1.2 < Math.abs(i) && (i *= w.stepSize / 120),
        1.2 < Math.abs(e) && (e *= w.stepSize / 120),
        h(s, i, e),
        t.preventDefault(),
        void d())))
    }
    function A(t) {
        var e = t.target
          , i = t.ctrlKey || t.altKey || t.metaKey || t.shiftKey && t.keyCode !== T.spacebar
          , s = (document.contains(v) || (v = document.activeElement),
        /^(button|submit|radio|checkbox|file|color|image)$/i);
        if (/^(textarea|select|embed|object)$/i.test(e.nodeName) || p(e, "input") && !s.test(e.type) || p(v, "video") || function(t) {
            var e = t.target
              , i = !1;
            if (-1 != document.URL.indexOf("www.youtube.com/watch"))
                do {
                    if (i = e.classList && e.classList.contains("html5-video-controls"))
                        break
                } while (e = e.parentNode);
            return i
        }(t) || e.isContentEditable || t.defaultPrevented || i)
            return !0;
        if ((p(e, "button") || p(e, "input") && s.test(e.type)) && t.keyCode === T.spacebar)
            return !0;
        var n = 0
          , o = 0
          , r = c(v)
          , a = r.clientHeight;
        switch (r == document.body && (a = window.innerHeight),
        t.keyCode) {
        case T.up:
            o = -w.arrowScroll;
            break;
        case T.down:
            o = w.arrowScroll;
            break;
        case T.spacebar:
            o = -(t.shiftKey ? 1 : -1) * a * .9;
            break;
        case T.pageup:
            o = .9 * -a;
            break;
        case T.pagedown:
            o = .9 * a;
            break;
        case T.home:
            o = -r.scrollTop;
            break;
        case T.end:
            var l = r.scrollHeight - r.scrollTop - a
              , o = 0 < l ? 10 + l : 0;
            break;
        case T.left:
            n = -w.arrowScroll;
            break;
        case T.right:
            n = w.arrowScroll;
            break;
        default:
            return !0
        }
        h(r, n, o),
        t.preventDefault(),
        d()
    }
    function B(t) {
        v = t.target
    }
    function d() {
        clearTimeout(H),
        H = setInterval(function() {
            P = {}
        }, 1e3)
    }
    function o(t, e) {
        for (var i = t.length; i--; )
            P[I(t[i])] = e;
        return e
    }
    function c(t) {
        var e = []
          , i = document.body
          , s = C.scrollHeight;
        do {
            var n = P[I(t)];
            if (n)
                return o(e, n);
            if (e.push(t),
            s === t.scrollHeight) {
                n = a(C) && a(i) || l(C);
                if (_ && r(C) || !_ && n)
                    return o(e, W())
            } else if (r(t) && l(t))
                return o(e, t)
        } while (t = t.parentElement)
    }
    function r(t) {
        return t.clientHeight + 10 < t.scrollHeight
    }
    function a(t) {
        return "hidden" !== getComputedStyle(t, "").getPropertyValue("overflow-y")
    }
    function l(t) {
        t = getComputedStyle(t, "").getPropertyValue("overflow-y");
        return "scroll" === t || "auto" === t
    }
    function u(t, e) {
        window.addEventListener(t, e, !1)
    }
    function p(t, e) {
        return (t.nodeName || "").toLowerCase() === e.toLowerCase()
    }
    function f(t, e) {
        return Math.floor(t / e) == t / e
    }
    function m(t) {
        return f(x[0], t) && f(x[1], t) && f(x[2], t)
    }
    function g(t) {
        var e;
        return ((t *= w.pulseScale) < 1 ? t - (1 - Math.exp(-t)) : (--t,
        (e = Math.exp(-1)) + (1 - Math.exp(-t)) * (1 - e))) * w.pulseNormalize
    }
    var v, t, w, y, _, i, b, C, x, k, T, E, S, s, e, H, F, I, P, z, M, N, W, D, O, j;
    $(document).ready(function() {
        (navigator.userAgent.match(/iPhone/) || navigator.userAgent.match(/iPod/)) && $("body").addClass("iphone-device"),
        navigator.userAgent.match(/Android/) && $("body").addClass("android-device");
        var e, h, d, c, t, i, s = !1, n = !1;
        function l(e) {
            var i, t;
            herald_js_settings.single_sticky_bar && (i = $(".herald-site-footer").offset().top - $(window).height(),
            $("body").hasClass("single") && e.find(".herald-single-sticky").length && (t = e.find(".herald-single-sticky").clone(!0, !0),
            e.append('<div class="herald-single-sticky herald-single-mobile-sticky hidden-lg hidden-md">' + t.html() + "</div>")),
            $(window).scroll(function() {
                var t = $(window).scrollTop();
                t >= e.offset().top + 600 && t < i ? e.addClass("herald-sticky-single-visible") : e.removeClass("herald-sticky-single-visible")
            }))
        }
        function o() {
            var t = "";
            $("body").hasClass("herald-boxed") ? (t = ".herald-no-sid .alignfull { width: " + $(".herald-site-content").outerWidth() + "px; margin-left: -" + $(".herald-site-content").outerWidth() / 2 + "px; margin-right: -" + $(".herald-site-content").outerWidth() / 2 + "px; left:50%; right:50%;max-width: none; }",
            $("#herald-full-fix").length ? $("#herald-full-fix").html(t) : $("head").append('<style id="herald-full-fix" type="text/css">' + t + "</style>")) : (t = ".herald-no-sid .alignfull { width: " + $(window).width() + "px; margin-left: -" + $(window).width() / 2 + "px; margin-right: -" + $(window).width() / 2 + "px; left:50%; right:50%;max-width: none; }",
            $("#herald-align-fix").length ? $("#herald-align-fix").html(t) : $("head").append('<style id="herald-align-fix" type="text/css">' + t + "</style>"))
        }
        function u(t) {
            t.find(".herald-widget-slider").each(function() {
                var t = $(this).closest(".widget").find(".herald-slider-controls");
                $(this).owlCarousel({
                    rtl: "true" === herald_js_settings.rtl_mode,
                    loop: !0,
                    autoHeight: !1,
                    autoWidth: !1,
                    nav: !0,
                    center: !1,
                    fluidSpeed: 100,
                    navContainer: t,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                    responsive: {
                        0: {
                            autoWidth: !1,
                            margin: 20,
                            items: 1
                        }
                    }
                })
            })
        }
        function p(t) {
            $("body").imagesLoaded(function() {
                t.find(".gallery-columns-1, .wp-block-gallery.columns-1").owlCarousel({
                    rtl: "true" === herald_js_settings.rtl_mode,
                    loop: !0,
                    nav: !0,
                    autoWidth: !1,
                    autoHeight: !0,
                    center: !1,
                    fluidSpeed: 100,
                    margin: 0,
                    items: 1,
                    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']
                })
            })
        }
        r(),
        $(window).resize(function() {
            document.fullscreenElement || document.mozFullScreenElement || document.webkitFullscreenElement || document.msFullscreenElement || (r(),
            C(),
            a(),
            x(),
            P(),
            $(".herald-responsive-header .herald-menu-popup-search.herald-search-active span").removeClass("fa-times").addClass("fa-search"),
            $(".herald-responsive-header .herald-menu-popup-search .herald-in-popup").removeClass("herald-search-active"))
        }),
        $("body").on("mouseenter", ".herald-fa-item", function() {
            var t = $(this).find(".entry-content").height();
            $(this).find(".entry-header").css({
                "-webkit-transform": "translate(0,-" + t + "px)",
                "-ms-transform": "translate(0,-" + t + "px)",
                transform: "translate(0,-" + t + "px)"
            })
        }),
        $("body").on("mouseleave", ".herald-fa-item", function() {
            $(this).find(".entry-header").css({
                "-webkit-transform": "translate(0,0)",
                "-ms-transform": "translate(0,0)",
                transform: "translate(0,0)"
            })
        }),
        $(".main-navigation").on("mouseenter", "li", function(t) {
            $(this).closest("body").width() < $(document).width() && $(this).find("ul").addClass("herald-rev")
        }),
        $(".herald-entry-content, .meta-media").fitVids({
            customSelector: ["iframe[src*='youtube.com/embed']", "iframe[src*='player.vimeo.com/video']", "iframe[src*='kickstarter.com/projects']", "iframe[src*='players.brightcove.net']", "iframe[src*='hulu.com/embed']", "iframe[src*='vine.co/v']", "iframe[src*='videopress.com/embed']", "iframe[src*='dailymotion.com/embed']", "iframe[src*='vid.me/e']", "iframe[src*='player.twitch.tv']", "iframe[src*='facebook.com/plugins/video.php']", "iframe[src*='gfycat.com/ifr/']", "iframe[src*='liveleak.com/ll_embed']", "iframe[src*='media.myspace.com']", "iframe[src*='archive.org/embed']", "iframe[src*='channel9.msdn.com']", "iframe[src*='content.jwplatform.com']", "iframe[src*='wistia.com']", "iframe[src*='vooplayer.com']", "iframe[src*='content.zetatv.com.uy']", "iframe[src*='embed.wirewax.com']", "iframe[src*='eventopedia.navstream.com']", "iframe[src*='cdn.playwire.com']", "iframe[src*='drive.google.com']", "iframe[src*='videos.sproutvideo.com']"].join(","),
            ignore: '[class^="wp-block"]'
        }),
        $("body").on("click", ".herald-site-header .herald-menu-popup-search span, .herald-header-sticky .herald-menu-popup-search span", function(t) {
            t.preventDefault(),
            $(this).toggleClass("fa-times", "fa-search"),
            $(this).parent().toggleClass("herald-search-active"),
            $('.herald-search-active input[type="text"]').focus(),
            $(this).closest("body").width() < $(document).width() ? $(this).siblings(".herald-in-popup").addClass("herald-reverse-form") : $(this).siblings(".herald-in-popup").removeClass("herald-reverse-form")
        }),
        $(".herald-responsive-header").on("click", ".herald-menu-popup-search span", function(t) {
            t.preventDefault(),
            $(this).next().toggle(),
            $(this).toggleClass("fa-times", "fa-search"),
            $(window).width() < 1250 && ($(".herald-responsive-header .herald-in-popup .herald-search-input").focus(),
            $(".herald-responsive-header .herald-in-popup").css("width", $(window).width()))
        }),
        $("body").on("mouseenter", ".herald-site-header .herald-mega-menu, .herald-header-sticky .herald-mega-menu", function() {
            var t = $(".herald-site-content").width();
            $(this).find("> .sub-menu").css("width", t + "px")
        }),
        $("body").on("mouseover mouseleave", ".entry-meta-wrapper .herald-share", function(t) {
            "mouseover" == t.type ? $(this).find(".meta-share-wrapper").slideDown() : $(this).find(".meta-share-wrapper").slideUp()
        }),
        window.matchMedia("(max-width: 766px)").matches && $(".herald-mod-subnav-mobile").each(function() {
            $(this).closest(".herald-mod-h").addClass("herald-subcat-dropdown")
        }),
        $("body").on("click", ".herald-subcat-dropdown", function() {
            $(this).find(".herald-mod-subnav-mobile").slideToggle(200)
        }),
        $("body").imagesLoaded(function() {
            C(),
            a(),
            x(),
            l($(".herald-site-content .herald-section").last())
        }),
        herald_js_settings.header_sticky && $(window).scroll(function() {
            var t = $(window).scrollTop();
            herald_js_settings.header_sticky_up ? t < e && t >= herald_js_settings.header_sticky_offset ? $("body").addClass("herald-sticky-header-visible") : $("body").removeClass("herald-sticky-header-visible") : t >= herald_js_settings.header_sticky_offset ? $("body").addClass("herald-sticky-header-visible") : $("body").removeClass("herald-sticky-header-visible"),
            e = t
        }),
        o(),
        b($(".herald-section")),
        herald_js_settings.popup_img && (y($(".herald-site-content")),
        _($(".herald-site-content"))),
        $("body").on("click", ".herald-comment-form-open", function(t) {
            t.preventDefault();
            var e = $(this).closest(".herald-section");
            $(this).parent().fadeOut(100, function() {
                e.find("#respond").fadeIn(300),
                e.find("#respond #comment").focus()
            })
        }),
        $("#jetpack_remote_comment").length && ($(".herald-comment-form-open").parent().hide(),
        $("#respond").fadeIn(300)),
        $("body").on("click", ".entry-meta-single .herald-comments a, .herald-comment-action", function(t) {
            t.preventDefault();
            var t = $(this).closest(".herald-section")
              , e = this.hash
              , i = t.find(e);
            t.find(".herald-comment-form-open").parent().hide(),
            t.find("#respond").show(),
            $("html, body").stop().animate({
                scrollTop: i.offset().top
            }, 900, "swing", function() {
                window.location.hash = e
            })
        }),
        $(".herald-slider").each(function() {
            var t = $(this).closest(".herald-module").find(".herald-slider-controls")
              , e = $(this).closest(".herald-module").attr("data-col")
              , i = t.attr("data-col")
              , e = e / i
              , s = 1e3 * parseInt(t.attr("data-autoplay"))
              , n = !!s;
            $(this).owlCarousel({
                rtl: "true" === herald_js_settings.rtl_mode,
                loop: !0,
                autoHeight: !1,
                autoWidth: !1,
                items: e,
                margin: 40,
                nav: !0,
                center: !1,
                fluidSpeed: 100,
                autoplay: n,
                autoplayHoverPause: !0,
                autoplayTimeout: s,
                navContainer: t,
                navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>'],
                responsive: {
                    0: {
                        margin: 20,
                        items: i <= 2 ? 2 : 1
                    },
                    768: {
                        margin: 30
                    },
                    1480: {
                        margin: 40
                    }
                }
            })
        }),
        $(".trending-slider").owlCarousel({
            rtl: "true" === herald_js_settings.rtl_mode,
            loop: !0,
            autoWidth: !1,
            items: herald_js_settings.trending_columns,
            nav: !1,
            center: !1,
            fluidSpeed: 100,
            margin: 20,
            autoplay: !0,
            autoplayHoverPause: !0,
            autoplayTimeout: 2500
        }),
        u($("body")),
        p($(".herald-section")),
        ($(".herald-infinite-scroll").length || $(".herald-load-more").length || $(".herald-infinite-scroll-single").length) && (h = [],
        c = d = 0,
        T = {
            prev: window.location.href,
            next: "",
            offset: $(window).scrollTop(),
            prev_title: window.document.title,
            next_title: window.document.title
        },
        h.push(T),
        i = 0,
        $(window).scroll(function() {
            h[d].offset != t && $(window).scrollTop() < h[d].offset && (t = h[d].offset,
            i = 0,
            window.document.title = h[d].prev_title,
            window.history.replaceState(h, "", h[d].prev),
            0 != (c = d) && d--),
            h[c].offset != i && $(window).scrollTop() > h[c].offset && (i = h[c].offset,
            t = 0,
            window.document.title = h[c].next_title,
            window.history.replaceState(h, "", h[c].next),
            (d = c) < h.length - 1 && c++)
        }));
        var f = 0
          , m = ($("body").on("click", ".herald-load-more a", function(t) {
            t.preventDefault();
            var o = $(this)
              , r = o.attr("href")
              , a = window.location.href
              , l = window.document.title;
            o.addClass("herald-loader-active"),
            $(".herald-loader").show(),
            $("<div>").load(r, function() {
                var t = f.toString()
                  , i = o.closest(".herald-module").find(".herald-posts")
                  , s = $(this).find(".herald-module:last article").addClass("herald-new-" + t)
                  , n = $(this);
                s.imagesLoaded(function() {
                    var t, e;
                    return s.hide().appendTo(i).fadeIn(400),
                    n.find(".herald-load-more").length ? ($(".herald-load-more").html(n.find(".herald-load-more").html()),
                    $(".herald-loader").hide(),
                    o.removeClass("herald-loader-active")) : $(".herald-load-more").fadeOut("fast").remove(),
                    C(!0),
                    r != window.location && (d++,
                    c++,
                    t = n.find("title").text(),
                    e = {
                        prev: a,
                        next: r,
                        offset: $(window).scrollTop(),
                        prev_title: l,
                        next_title: t
                    },
                    h.push(e),
                    window.document.title = t,
                    window.history.pushState(e, "", r)),
                    f++,
                    !1
                })
            })
        }),
        !0)
          , g = ($(".herald-infinite-scroll").length && $(window).scroll(function() {
            var e, o, r, a;
            m && $(".herald-infinite-scroll").length && $(this).scrollTop() > $(".herald-infinite-scroll").offset().top - $(this).height() - 200 && (e = $(".herald-infinite-scroll a"),
            o = e.attr("href"),
            r = window.location.href,
            a = window.document.title,
            null != o && (m = !1,
            $(".herald-loader").show(),
            $("<div>").load(o, function() {
                var t = f.toString()
                  , i = e.closest(".herald-module").find(".herald-posts")
                  , s = $(this).find(".herald-module:last article").addClass("herald-new-" + t)
                  , n = $(this);
                s.imagesLoaded(function() {
                    var t, e;
                    return s.hide().appendTo(i).fadeIn(400),
                    n.find(".herald-infinite-scroll").length ? ($(".herald-infinite-scroll").html(n.find(".herald-infinite-scroll").html()),
                    $(".herald-loader").hide(),
                    m = !0) : $(".herald-infinite-scroll").fadeOut("fast").remove(),
                    C(!0),
                    o != window.location && (d++,
                    c++,
                    t = n.find("title").text(),
                    e = {
                        prev: r,
                        next: o,
                        offset: $(window).scrollTop(),
                        prev_title: a,
                        next_title: t
                    },
                    h.push(e),
                    window.document.title = t,
                    window.history.pushState(e, "", o)),
                    f++,
                    !1
                })
            })))
        }),
        !0)
          , v = 0
          , w = !0;
        function r() {
            1 < window.devicePixelRatio && (!s && herald_js_settings.logo_retina && $(".herald-logo").length && ($(".herald-logo").imagesLoaded(function() {
                $(".herald-logo").each(function() {
                    var t;
                    $(this).is(":visible") && (t = $(this).width(),
                    $(this).attr("src", herald_js_settings.logo_retina).css("width", t + "px"))
                })
            }),
            s = !0),
            !n && herald_js_settings.logo_mini_retina && $(".herald-logo-mini").length && ($(".herald-logo-mini").imagesLoaded(function() {
                $(".herald-logo-mini").each(function() {
                    var t;
                    $(this).is(":visible") && (t = $(this).width(),
                    $(this).attr("src", herald_js_settings.logo_mini_retina).css("width", t + "px"))
                })
            }),
            n = !0))
        }
        function y(t) {
            t.find(".herald-image-format").magnificPopup({
                type: "image",
                image: {
                    titleSrc: function(t) {
                        return t.el.find("figure").text()
                    }
                }
            })
        }
        function _(t) {
            t.find(".gallery, .wp-block-gallery").each(function() {
                var e = $(this)
                  , t = e.hasClass("wp-block-gallery") ? "figure a" : ".gallery-icon a.herald-popup";
                $(this).find(t).magnificPopup({
                    type: "image",
                    gallery: {
                        enabled: !0
                    },
                    image: {
                        titleSrc: function(t) {
                            t = e.hasClass("wp-block-gallery") ? t.el.closest("figure").find("figcaption") : t.el.closest(".gallery-item").find(".gallery-caption");
                            return "undefined" != t ? t.text() : ""
                        }
                    }
                })
            })
        }
        function b(t) {
            t.find("a.herald-popup-img").length && ((t = t.find("a.herald-popup-img")).find("img").each(function() {
                var t = $(this);
                t.hasClass("alignright") && t.removeClass("alignright").parent().addClass("alignright"),
                t.hasClass("alignleft") && t.removeClass("alignleft").parent().addClass("alignleft"),
                t.hasClass("alignnone") && t.removeClass("alignnone").parent().addClass("alignnone"),
                t.hasClass("aligncenter") && t.removeClass("aligncenter").parent().addClass("aligncenter")
            }),
            t.magnificPopup({
                type: "image",
                gallery: {
                    enabled: !0
                },
                image: {
                    titleSrc: function(t) {
                        return t.el.closest(".wp-caption").find("figcaption").text()
                    }
                }
            }))
        }
        function C(t) {
            1250 <= $(window).width() ? $(".herald-sticky").length && ($(".herald-sidebar").each(function() {
                var t = $(this).closest(".herald-section")
                  , t = t.find(".herald-ignore-sticky-height").length ? t.height() - 40 - t.find(".herald-ignore-sticky-height").height() : t.height() - 40;
                $(this).css("min-height", t)
            }),
            t && "absolute" == $(".herald-sticky").last().css("position") && $(".herald-sticky").last().css("position", "fixed").css("top", 100),
            $(".herald-sticky").stick_in_parent({
                parent: ".herald-sidebar",
                offset_top: 100
            })) : ($(".herald-sidebar").each(function() {
                $(this).css("height", "auto"),
                $(this).css("min-height", "1px")
            }),
            $(".herald-sticky").trigger("sticky_kit:detach"))
        }
        function a() {
            $(".herald-sidebar-left").each(function() {
                $(window).width() < 1250 ? $(this).parent().find("div").first().hasClass("herald-sidebar-left") && $(this).insertAfter($(this).parent().find(".col-mod-main")) : $(this).parent().find("div").first().hasClass("herald-sidebar-left") || $(this).insertBefore($(this).parent().find(".col-mod-main"))
            })
        }
        function x() {
            768 <= $(window).width() ? $(".entry-meta-wrapper-sticky").length && ($(".entry-meta-wrapper-sticky").each(function() {
                var t = $(this).parent()
                  , e = $(this).closest(".herald-section").find(".herald-entry-content").parent().height() - 70;
                t.css("min-height", e)
            }),
            $(".entry-meta-wrapper-sticky").stick_in_parent()) : ($(".entry-meta-wrapper-sticky").each(function() {
                $(this).css("height", "auto"),
                $(this).css("min-height", "1px")
            }),
            $(".entry-meta-wrapper-sticky").trigger("sticky_kit:detach"))
        }
        $(".herald-infinite-scroll-single").length && $(window).scroll(function() {
            var o, r, a;
            g && $(".herald-infinite-scroll-single").length && $(this).scrollTop() > $(".herald-infinite-scroll-single").offset().top - $(this).height() - 200 && (o = $(".herald-infinite-scroll-single a").css("opacity", 0).attr("href"),
            r = window.location.href,
            a = window.document.title,
            null != o && (g = !1,
            $(".herald-loader").show(),
            $("<div>").load(o, function() {
                var i = $(this)
                  , t = v.toString()
                  , s = $(".herald-site-content").find(".herald-section").last()
                  , n = i.find(".herald-section").last().addClass("herald-new-" + t);
                n.imagesLoaded(function() {
                    n.hide().insertAfter(s).fadeIn(400),
                    i.find(".herald-infinite-scroll-single").length ? ($(".herald-infinite-scroll-single").html(i.find(".herald-infinite-scroll-single").html()),
                    $(".herald-loader").hide(),
                    g = !0) : $(".herald-infinite-scroll-single").fadeOut("fast").remove(),
                    C(!0),
                    p(n),
                    u(n),
                    l(n),
                    x(),
                    b(n),
                    herald_js_settings.popup_img && (y(n),
                    _(n));
                    var t, e = {};
                    return w && (e = {
                        prev: window.location.href,
                        next: "",
                        offset: 0,
                        prev_title: window.document.title,
                        next_title: window.document.title
                    },
                    window.history.pushState(e, "", window.location.href),
                    w = !1),
                    o != window.location && (d++,
                    c++,
                    t = i.find("title").text(),
                    e = {
                        prev: r,
                        next: o,
                        offset: $(window).scrollTop(),
                        prev_title: a,
                        next_title: t
                    },
                    h.push(e),
                    window.document.title = t,
                    window.history.pushState(e, "", o)),
                    v++,
                    !1
                })
            })))
        });
        var k, T, E = $(".herald-slide"), S = $(".herald-site-content");
        function I() {
            E.removeClass("open").addClass("close"),
            $("body").removeClass("herald-menu-open")
        }
        function P() {
            var t;
            herald_js_settings.header_ad_responsive && (t = $("#header .herald-da"),
            $(window).width() < herald_js_settings.header_responsive_breakpoint && !t.hasClass("cloned") && (t.addClass("cloned"),
            t.clone().removeClass("cloned hidden-xs").addClass("header-mobile-da").insertAfter("#herald-responsive-header")))
        }
        P(),
        0 != herald_js_settings.responsive_menu_more_link && (k = $(".herald-more-link-wrapper"),
        (T = $(".herald-mobile-nav .secondary-navigation")).length && T.each(function() {
            var t = $(this).find("ul").first().children();
            k.find(".herald-more-link ul").append(t),
            $(this).remove()
        })),
        $(".herald-nav-toggle").on("click", function() {
            S.hasClass("open") ? I() : (E.removeClass("close").addClass("open"),
            $("body").addClass("herald-menu-open"))
        }),
        $("body").on("click", ".herald-site-content", function() {
            S.hasClass("open") && I()
        }),
        $(".herald-mobile-nav li").each(function() {
            var t, e = $(this);
            e.closest("nav").removeClass("herald-menu").addClass("herald-mob-nav"),
            (e.hasClass("menu-item-has-children") || e.hasClass("herald-mega-menu")) && e.append('<span class="herald-menu-toggler fa fa-caret-down"></span>'),
            e.hasClass("herald-mega-menu") && (t = e.find(".herald-mega-menu-sub-cats ul li"),
            e.find(".container").remove(),
            e.find(".sub-menu").append(t))
        }),
        /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) ? $(".herald-menu-toggler").on("touchstart", function(t) {
            $(this).prev().slideToggle(),
            $(this).parent().toggleClass("herald-current-mobile-item")
        }) : $(".herald-menu-toggler").on("click", function(t) {
            $(this).prev().slideToggle(),
            $(this).parent().toggleClass("herald-current-mobile-item")
        }),
        $("body").on("click", ".herald-responsive-header .herald-menu-popup-search span", function(t) {
            t.preventDefault(),
            t.stopPropagation(),
            $(window).width() < 1250 && $(".herald-responsive-header .herald-in-popup").css("width", $(window).width())
        }),
        $("#back-top").length && ($(window).scroll(function() {
            400 < $(this).scrollTop() ? $("#back-top").fadeIn() : $("#back-top").fadeOut()
        }),
        $("body").on("click", "#back-top", function() {
            return $("body,html").animate({
                scrollTop: 0
            }, 800),
            !1
        })),
        $(window).resize(function() {
            o()
        })
    }),
    herald_js_settings.smooth_scroll && (w = t = {
        frameRate: 150,
        animationTime: 400,
        stepSize: 100,
        pulseAlgorithm: !0,
        pulseScale: 4,
        pulseNormalize: 1,
        accelerationDelta: 50,
        accelerationMax: 3,
        keyboardSupport: !0,
        arrowScroll: 50,
        touchpadSupport: !1,
        fixedBackground: !0,
        excluded: ""
    },
    _ = y = !1,
    b = !(i = {
        x: 0,
        y: 0
    }),
    C = document.documentElement,
    x = [],
    k = /^Mac/.test(navigator.platform),
    T = {
        left: 37,
        up: 38,
        right: 39,
        down: 40,
        spacebar: 32,
        pageup: 33,
        pagedown: 34,
        end: 35,
        home: 36
    },
    w = t,
    S = !(E = []),
    s = Date.now(),
    e = 0,
    I = function(t) {
        return t.uniqueID || (t.uniqueID = e++)
    }
    ,
    P = {},
    window.localStorage && localStorage.SS_deltaBuffer && (x = localStorage.SS_deltaBuffer.split(",")),
    M = window.requestAnimationFrame || window.webkitRequestAnimationFrame || window.mozRequestAnimationFrame || function(t, e, i) {
        window.setTimeout(t, i || 1e3 / 60)
    }
    ,
    N = window.MutationObserver || window.WebKitMutationObserver || window.MozMutationObserver,
    W = function() {
        var t, e;
        return z || ((t = document.createElement("div")).style.cssText = "height:10000px;width:1px;",
        document.body.appendChild(t),
        e = document.body.scrollTop,
        document.documentElement.scrollTop,
        window.scrollBy(0, 1),
        z = document.body.scrollTop != e ? document.body : document.documentElement,
        window.scrollBy(0, -1),
        document.body.removeChild(t)),
        z
    }
    ,
    t = window.navigator.userAgent,
    O = /Edge/.test(t),
    j = /chrome/i.test(t) && !O,
    O = /safari/i.test(t) && !O,
    t = /mobile/i.test(t),
    j = (j || O) && !t,
    "onwheel"in document.createElement("div") ? D = "wheel" : "onmousewheel"in document.createElement("div") && (D = "mousewheel"),
    D && j && (u(D, L),
    u("mousedown", B),
    u("load", n)))
}(jQuery);
