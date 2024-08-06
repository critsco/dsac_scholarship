var staticCacheName = "pwa-v" + new Date().getTime();
var filesToCache = [
    "/offline",
    "/css/app.css",
    "/js/app.js",
    "/images/logo.png",
    "/images/logo_sidemenu.png",
    "/images/icons/ios/72.png",
    "/images/icons/android/android-launchericon-96-96.png",
    "/images/icons/ios/128.png",
    "/images/icons/ios/144.png",
    "/images/icons/ios/152.png",
    "/images/icons/ios/192.png",
    "/images/icons/ios/256.png",
    "/images/icons/ios/512.png",
    "/images/icons/splash_screens/4__iPhone_SE__iPod_touch_5th_generation_and_later_portrait.png",
    "/images/icons/splash_screens/iPhone_8__iPhone_7__iPhone_6s__iPhone_6__4.7__iPhone_SE_portrait.png",
    "/images/icons/splash_screens/iPhone_11__iPhone_XR_portrait.png",
    "/images/icons/splash_screens/iPhone_13_mini__iPhone_12_mini__iPhone_11_Pro__iPhone_XS__iPhone_X_portrait.png",
    "/images/icons/splash_screens/iPhone_8_Plus__iPhone_7_Plus__iPhone_6s_Plus__iPhone_6_Plus_portrait.png",
    "/images/icons/splash_screens/iPhone_11_Pro_Max__iPhone_XS_Max_portrait.png",
    "/images/icons/splash_screens/9.7__iPad_Pro__7.9__iPad_mini__9.7__iPad_Air__9.7__iPad_portrait.png",
    "/images/icons/splash_screens/10.5__iPad_Air_portrait.png",
    "/images/icons/splash_screens/11__iPad_Pro__10.5__iPad_Pro_portrait.png",
    "/images/icons/splash_screens/12.9__iPad_Pro_portrait.png",
];

// Cache on install
self.addEventListener("install", (event) => {
    this.skipWaiting();
    event.waitUntil(
        caches.open(staticCacheName).then((cache) => {
            return cache.addAll(filesToCache);
        })
    );
});

// Clear cache on activate
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName.startsWith("pwa-"))
                    .filter((cacheName) => cacheName !== staticCacheName)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});

// Serve from Cache
self.addEventListener("fetch", (event) => {
    event.respondWith(
        caches
            .match(event.request)
            .then((response) => {
                return response || fetch(event.request);
            })
            .catch(() => {
                return caches.match("offline");
            })
    );
});
