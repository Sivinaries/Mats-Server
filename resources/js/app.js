import './bootstrap';

if ('serviceWorker' in navigator) {
    window.addEventListener('load', () => {
        navigator.serviceWorker.register('/sw.js', { scope: '/' }).then(function (registration) {
            console.log('Service Worker registered successfully!', registration);
        }).catch(function (registrationError) {
            console.log('Service Worker registration failed:', registrationError);
        });
    });
}
