<script>
export default {
    props: {
        id: { type: Number, required: true },
    },
    methods: {
        emitEvent(event) {
            window.EventBus.$emit(event, this.id);
        },
    }
}
</script>
