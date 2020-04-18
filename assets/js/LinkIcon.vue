<script>
export default {
    methods: {
        emitEvent(event, id) {
            window.EventBus.$emit(event, id);
        },
    }
}
</script>
