import './bootstrap';
import { gsap } from 'gsap';

window.gsap = gsap;

import './bootstrap';
// GSAP කෙලින්ම import කරනවා
import { gsap } from 'gsap';

document.addEventListener('DOMContentLoaded', () => {
    // Nav links ටික අල්ලගන්නවා
    const navLinks = document.querySelectorAll('.nav-link');
    
    navLinks.forEach(link => {
        link.addEventListener('mouseenter', () => {
            gsap.to(link, { scale: 1.05, duration: 0.3, ease: "back.out(1.7)" });
        });
        
        link.addEventListener('mouseleave', () => {
            gsap.to(link, { scale: 1, duration: 0.2, ease: "power1.inOut" });
        });
    });
});