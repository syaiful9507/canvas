import axios from 'axios';

export default axios.create({
    baseURL: window.Canvas.path,
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
    }
});
