export default {
    data() {
        return {
            extensions: {
                pdf: 'file-pdf',
                txt: 'file-text',
                'gif|jpe?g|png|bpm|webp|svg|ico': 'file-image',
                'mpeg|ogv|mp4|m4v|webm|wmv': 'file-video',
                'ogg|wma|mp3|m4a|aac': 'file-audio',
                'xls|xlsx': 'file-excel',
                'doc|docx': 'file-word',
                'zip|7z|rar|tar.gz': 'file-archive'
            }
        };
    },

    methods: {
        isImage(url) {
            return url.match(/\.(?:gif|jpe?g|png|bpm|webp|svg|ico)$/i);
        },

        isVideo(url) {
            return url.match(/\.(mpeg|ogv|mp4|m4v|webm|wmv)$/i);
        },

        isFileExt(name, ext) {
            const regex = `(?:${ext})$`;
            return name.match(new RegExp(regex, 'i'));
        },

        UKIcon(name) {
            const ext = _.filter(this.extensions, (value, key) => {
                const m = this.isFileExt(name, key);
                return m && m[0];
            })[0];

            if (ext) return ext;
            return this.extensions.txt;
        }
    }
};
