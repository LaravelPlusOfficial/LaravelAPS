Vue.mixin({
    methods: {
        /**
         * Format the given date with respect to timezone.
         */
        formatDate(unixTime){
            return moment(unixTime * 1000).add(new Date().getTimezoneOffset() / 60)
        },


        /**
         * Extract the job base name.
         */
        jobBaseName(name){
            if (!name.includes('\\')) return name;

            var parts = name.split("\\");

            return parts[parts.length - 1];
        },


        /**
         * Convert to human readable timestamp.
         */
        readableTimestamp(timestamp){
            return this.formatDate(timestamp).format('YY-MM-DD HH:mm:ss');
        },


        /**
         * Convert to human readable timestamp.
         */
        displayableTagsList(tags){
            if (!tags || !tags.length) return '';

            return _.reduce(tags, (s, n)=> {
                return (s ? s + ', ' : '') + _.truncate(n);
            }, '');
        }
    }
});