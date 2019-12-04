function outPlayerWidget(e) {
    var t, i, l, d;
    t = function(t) {
        var i, l, d = 0,
            n = 0,
            s = [],
            a = document.getElementById(e.widgetDivId),
            o = document.getElementById(e.videoPlayerId),
            r = (document.querySelector(".jw-" + h), a.querySelector(".jw-widget-content")),
            c = a.querySelector(".next"),
            u = a.querySelector(".previous"),
            g = a.querySelectorAll(".icon"),
            f = a.querySelectorAll(".jw-widget-item"),
            m = jwplayer(o),
            h = e.widgetLayout || "spotlight",
            v = e.widgetSize || "", //"large",
            w = e.textColor || "#fff",
            y = e.backgroundColor || "#000",
            p = e.iconColor || "#fff",
            L = e.header || "Discover More Videos";
        if (a.classList.add(v), a.querySelector(".jw-widget-title").innerText = L, a.style.backgroundColor = y, a.style.color = w, g.forEach(function(e) { e.style.fill = p }), f.forEach(function(e) {
                e.addEventListener("click", function() {
                    var t = "https://cdn.jwplayer.com/v2/media/" + e.dataset.mediaid;
                    m.load(t), m.getViewable() || document.getElementById(o.id).scrollIntoView({ behavior: "smooth" }), m.on("playlistItem", function() { m.play() })
                })
            }), "spotlight" === h) {
            var j = document.createElement("div");
            j.innerHTML = '<svg width="477.9px" height="501.1px" viewBox="0 0 477.9 501.1" style="enable-background:new 0 0 477.9 501.1;" xml:space="preserve" fill="#fff"><g transform="translate(887 1706)"><path d="M-875.6-1206.4c-3.1,2.4-7.5,1.8-9.9-1.3c-1.2-1.6-1.7-3.6-1.4-5.5v-484.5c-0.7-3.8,1.9-7.5,5.7-8.2 c2-0.3,4,0.2,5.5,1.4l461.9,243.1c6.2,3.3,6.2,8.6,0,11.9L-875.6-1206.4z"/></g></svg>';
            var S = a.querySelector(".jw-widget-item");
            i = document.querySelector(".jw-widget-item").offsetWidth + 10, S.classList.add("spotlight"), j.src = "src/img/play.svg", j.classList.add("jw-play-button"), S.append(j)
        } else "shelf" === h && (i = E());

        function E() { return a.offsetWidth } //return "large" === v ? 960 : "medium" === v ? 1120 : "small" === v ? 380 : void 0 }

        function q() { if ("spotlight" === h) { if ("large" === v) return t.length - 3; if ("medium" === v) return t.length - 2; if ("small" === v) return t.length - 1 } else if ("shelf" === h) { for ("large" === v ? l = 4 : "medium" === v ? l = 3 : "small" === v && (l = 2); t.length > 0;) s.push(t.splice(0, l)); return s.length - 1 } }

        function b(e) { d += e, "spotlight" === h && (S.classList.remove("spotlight"), S.removeChild(j), (S = document.getElementById(h + "-item-" + d)).classList.add("spotlight"), S.append(j), j.classList.add("fade")) } c.addEventListener("click", function() {
            var e = q() - 1,
                t = s[s.length - 1];
            if (d === e && c.classList.add("disabled"), d === q()) return !1;
            if ("shelf" === h && d === e && t.length < l) {
                var a = t.length % l;
                i = document.querySelector(".jw-widget-item").offsetWidth * a
            }
            u.classList.remove("disabled"), n -= i, r.style.left = n + "px", b(1)
        }), u.addEventListener("click", function() {
            q();
            var e = s[s.length - 1];
            if ("shelf" === h && d === q() && e.length < l) {
                var t = e.length % l;
                i = document.querySelector(".jw-widget-item").offsetWidth * t + t
            } else i = E();
            if (0 === d) return u.classList.add("disabled"), !1;
            1 === d && u.classList.add("disabled"), n += i, r.style.left = n + "px", c.classList.remove("disabled"), b(-1)
        })
    }, i = function(i) {
        var l = [],
            d = document.getElementById(e.widgetDivId);
        if (d) { //custom
            d.classList.add(e.widgetLayout);
            var n = d.querySelector(".jw-widget-content");
            i.playlist.forEach(function(t, i) { l.push("<div id='" + e.widgetLayout + "-item-" + i + "' data-mediaid='" + t.mediaid + "' class='jw-widget-item'><div class='jw-content-image'><img src='" + t.image + "'/></div><div class='jw-content-title'>" + t.title + "</div></div>"), n.insertAdjacentHTML("beforeend", l[i]) }), t(l)
        }
    }, l = e.playlist, (d = new XMLHttpRequest).open("GET", l, !0), d.onreadystatechange = function() {
        if (4 === d.readyState && d.status >= 200) {
            var e = JSON.parse(d.responseText);
            i(e)
        }
    }, d.send()
}