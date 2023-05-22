import axios from 'axios'

let instance = axios.create()
let csrf = document.head.querySelector('meta[name="csrf-token"]').content

instance.defaults.headers.common['X-CSRF-TOKEN'] = csrf
instance.defaults.baseURL = window.Canvas.path

const requestHandler = request => {
    // Add any request modifiers...
    return request
}

const errorHandler = error => {
    // Add any error modifiers...
    switch (error.response.status) {
        case 401:
        case 405:
            window.location.href = window.Canvas.path

            break
        default:
            break
    }

    return Promise.reject({ ...error })
}

const successHandler = response => {
    // Add any response modifiers...
    return response
}

instance.interceptors.request.use(request => requestHandler(request))

instance.interceptors.response.use(
    response => successHandler(response),
    error => errorHandler(error)
)

export default instance
