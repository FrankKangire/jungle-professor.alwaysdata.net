/* ============================================
   JUNGLE PROFESSOR - BACKGROUND SLIDESHOW
   ============================================ */

const JP_IMAGES = {

    // --- GENERAL IMAGES ---
    // Used on: index.php, login.php, signup.php, welcome.php
    general: [
        'images/general/andrea-cau-nV7GJmSq3zc-unsplash.jpg',
        'images/general/boris-smokrovic-DPXytK8Z59Y-unsplash.jpg',
        'images/general/denys-nevozhai-HhmCIJTLuGY-unsplash.jpg',
        'images/general/gary-bendig-6GMq7AGxNbE-unsplash.jpg',
        'images/general/gwen-weustink-I3C1sSXj1i8-unsplash.jpg',
        'images/general/hans-jurgen-mager-Fbdr6gXJBVY-unsplash.jpg',
        'images/general/jonatan-pie-xgTMSz6kegE-unsplash.jpg',
        'images/general/matthew-henry-VviFtDJakYk-unsplash.jpg',
        'images/general/pedro-lastra-Nyvq2juw4_o-unsplash.jpg',
        'images/general/premium_photo-1669725687137-75f7a1377720.jpg',
        'images/general/sid-balachandran-_9a-3NO5KJE-unsplash.jpg',
        'images/general/wexor-tmg-L-2p8fapOA8-unsplash.jpg',
    ],

    // --- JUNGLE IMAGES ---
    // Used on: server.php when gameType == 'jungle'
    jungle: [
        'images/jungle/boris-smokrovic-DPXytK8Z59Y-unsplash.jpg',
        'images/jungle/gary-bendig-6GMq7AGxNbE-unsplash.jpg',
        'images/jungle/gwen-weustink-I3C1sSXj1i8-unsplash.jpg',
        'images/jungle/hans-jurgen-mager-Fbdr6gXJBVY-unsplash.jpg',
        'images/jungle/jonatan-pie-xgTMSz6kegE-unsplash.jpg',
        'images/jungle/premium_photo-1669725687137-75f7a1377720.jpg',
        'images/jungle/sid-balachandran-_9a-3NO5KJE-unsplash.jpg',
        'images/jungle/wexor-tmg-L-2p8fapOA8-unsplash.jpg',
    ],

    // --- CITY IMAGES ---
    // Used on: server.php when gameType == 'city'
    city: [
        'images/city/andrea-cau-nV7GJmSq3zc-unsplash.jpg',
        'images/city/denys-nevozhai-HhmCIJTLuGY-unsplash.jpg',
        'images/city/matthew-henry-VviFtDJakYk-unsplash.jpg',
        'images/city/pedro-lastra-Nyvq2juw4_o-unsplash.jpg',
    ]
};

class JPSlideshow {
    constructor(type = 'general') {
        this.images = JP_IMAGES[type] || JP_IMAGES.general;
        this.currentIndex = 0;
        this.slides = [];
        this.interval = null;
    }

    init() {
        const container = document.createElement('div');
        container.className = 'jp-bg';

        const overlay = document.createElement('div');
        overlay.className = 'jp-bg-overlay';
        document.body.appendChild(overlay);

        this.images.forEach((src, i) => {
            const slide = document.createElement('div');
            slide.className = 'jp-bg-slide' + (i === 0 ? ' active' : '');
            slide.style.backgroundImage = `url('${src}')`;
            container.appendChild(slide);
            this.slides.push(slide);
        });

        document.body.prepend(container);

        if (this.slides.length > 1) {
            this.interval = setInterval(() => this.next(), 5000);
        }
    }

    next() {
        this.slides[this.currentIndex].classList.remove('active');
        this.currentIndex = (this.currentIndex + 1) % this.slides.length;
        this.slides[this.currentIndex].classList.add('active');
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const type = document.body.dataset.jpBg || 'general';
    const slideshow = new JPSlideshow(type);
    slideshow.init();
});
