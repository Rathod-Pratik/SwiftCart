/**
 * S3 Signed URL helper.
 *
 * Fetches a presigned GET URL from the backend for a given S3 key.
 * The backend endpoint is GET /api/s3/sign?key=<key>.
 *
 * Usage:
 *   import { s3Url, s3Urls } from './s3';
 *
 *   const url = await s3Url('profile-images/1/uuid.jpg');
 *   const map = await s3Urls(['a.jpg', 'b.jpg']);
 */

const S3_SIGN_ENDPOINT = '/api/s3/sign';

/**
 * Fetch a signed URL for a single S3 key.
 * Returns null if the key is falsy or the file doesn't exist.
 *
 * @param {string|null} key
 * @param {number} [expires=60] - expiry in minutes
 * @returns {Promise<string|null>}
 */
export async function s3Url(key, expires = 60) {
    if (!key) return null;

    if (Array.isArray(key)) {
        key = key[0];
        if (!key) return null;
    }

    if (typeof key !== 'string') return null;

    // Already a full URL – return as-is
    if (key.startsWith('http://') || key.startsWith('https://')) {
        return key;
    }

    try {
        const params = new URLSearchParams({ key, expires });
        const response = await fetch(`${S3_SIGN_ENDPOINT}?${params}`, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) return null;
        const data = await response.json();
        return data.url ?? null;
    } catch {
        return null;
    }
}

/**
 * Fetch signed URLs for multiple S3 keys in a single request.
 *
 * @param {string[]} keys
 * @param {number} [expires=60]
 * @returns {Promise<Record<string, string|null>>}
 */
export async function s3Urls(keys, expires = 60) {
    if (!keys || keys.length === 0) return {};

    // Separate already-full URLs from S3 keys
    const result = {};
    const s3Keys = [];

    for (const key of keys) {
        if (!key) {
            result[key] = null;
        } else if (key.startsWith('http://') || key.startsWith('https://')) {
            result[key] = key;
        } else {
            s3Keys.push(key);
        }
    }

    if (s3Keys.length === 0) return result;

    try {
        const params = new URLSearchParams({ expires });
        s3Keys.forEach((k) => params.append('keys[]', k));

        const response = await fetch(`${S3_SIGN_ENDPOINT}?${params}`, {
            headers: { Accept: 'application/json' },
            credentials: 'same-origin',
        });

        if (!response.ok) {
            s3Keys.forEach((k) => (result[k] = null));
            return result;
        }

        const data = await response.json();
        Object.assign(result, data.urls ?? {});
    } catch {
        s3Keys.forEach((k) => (result[k] = null));
    }

    return result;
}

/**
 * Resolve an image src: if the value is an S3 key, fetch a signed URL.
 * Falls back to the `fallback` src on error.
 *
 * @param {HTMLImageElement} imgEl
 * @param {string|null} key
 * @param {string} [fallback='']
 */
export async function setS3ImgSrc(imgEl, key, fallback = '') {
    if (!imgEl) return;
    if (!key) {
        imgEl.src = fallback;
        return;
    }
    const url = await s3Url(key);
    imgEl.src = url ?? fallback;
}
