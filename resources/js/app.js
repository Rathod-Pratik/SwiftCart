//

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allow your team to quickly build robust real-time web applications.
 */

import './echo';
import ApexCharts from 'apexcharts';
import { s3Url, s3Urls, setS3ImgSrc } from './s3';

window.ApexCharts = ApexCharts;
window.s3Url = s3Url;
window.s3Urls = s3Urls;
window.setS3ImgSrc = setS3ImgSrc;

/**
 * Auto-load any <img data-s3-key="..." data-s3-auto="true"> elements on page load.
 * Also resolves <img data-s3-key="..." class="...s3-img..."> elements.
 */
document.addEventListener('DOMContentLoaded', async () => {
    const autoImgs = document.querySelectorAll('img[data-s3-key][data-s3-auto="true"], img[data-s3-key].s3-img');
    if (autoImgs.length === 0) return;

    // Collect unique keys
    const keys = [...new Set([...autoImgs].map(img => img.dataset.s3Key).filter(Boolean))];
    if (keys.length === 0) return;

    const urlMap = await s3Urls(keys);

    autoImgs.forEach(img => {
        const key = img.dataset.s3Key;
        const url = urlMap[key];
        if (url) {
            img.src = url;
            img.classList.remove('hidden');
            // Hide sibling initials span if present
            const sibling = img.nextElementSibling;
            if (sibling && sibling.tagName === 'SPAN') sibling.classList.add('hidden');
        }
    });
});
