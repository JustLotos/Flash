<template>
    <v-row class="mb-6" justify="center" no-gutters>
        <panel-controls/>
    </v-row>
</template>

<script>
import PanelControls from "./PanelControls";
export default {
    name: "AnswerPanel",
    components: {PanelControls}
}
</script>