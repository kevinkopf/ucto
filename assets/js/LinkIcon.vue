<script>
export default {
    props: {
        args: { type: null, required: true },
    },
    methods: {
        emitEvent(event) {
            this.$root.$emit(event, this.args);
        },
    }
}
</script>
