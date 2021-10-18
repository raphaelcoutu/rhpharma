<template>
    <div>
        <h3>Log</h3>
        <div class="well" style="overflow:auto; height: 200px;" ref="messageBox">
            <span v-for="message in messages">
                {{ message }} <br>
            </span>

        </div>
    </div>
</template>

<script>
    export default {
        props: ['dataSchedule'],
        mounted() {
            let messageBox = this.$refs.messageBox;
            messageBox.scrollTop = messageBox.scrollHeight;

            Echo.channel('build-message')
                .listen('BuildMessageGenerated', (e) => {
                    if(e.schedule.id === this.dataSchedule.id) {
                        this.messages.push(e.timestamp + '\t ' + e.message);
                    }
                });
        },

        updated() {
            let messageBox = this.$refs.messageBox;
            messageBox.scrollTop = messageBox.scrollHeight;
        },

        data: () => ({
            messages: []
        })

    }
</script>