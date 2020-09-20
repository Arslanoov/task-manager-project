module.exports = {
    css: {
        loaderOptions: {
            sass: {
                prependData: `@import "@/assets/variables/main.scss";`
            }
        }
    }
};