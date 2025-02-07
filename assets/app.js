/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import './styles/app.css';

// console.log('This log comes from assets/app.js - welcome to AssetMapper! 🎉');

const burger_menu = document.getElementById("burger-menu");
const mobile_log_links = document.getElementById("mobile-log-links");
burger_menu.onclick = () => {
    burger_menu.classList.toggle("burger-active");
    mobile_log_links.classList.toggle("hidden");
}
