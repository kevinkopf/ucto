<script>
export default {
    props: {
        args: { type: null, required: true },
    },
    methods: {
        emitEvent(event) {
            window.EventBus.$emit(event, this.args);
        },
    }
}
</script>
