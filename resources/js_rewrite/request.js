import axios from 'axios';
import { store } from "@/store";

export default axios.create({
    baseURL: store.state.settings.path,
    headers: {
        'X-CSRF-TOKEN': document.head.querySelector('meta[name="csrf-token"]').content
    }
});
