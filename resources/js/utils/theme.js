function setDarkTheme() {
    document.documentElement.classList.add('dark')
}

function setLightTheme() {
    document.documentElement.classList.remove('dark')
}

function prefersDarkColorScheme() {
    return window.matchMedia('(prefers-color-scheme: dark)').matches
}

export { setDarkTheme, setLightTheme, prefersDarkColorScheme }
