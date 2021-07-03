<template>
  <v-sheet
      class="mx-auto rounded transition-swing"
      elevation="1"
  >
    <div class="editorx_body">
      <div :ref="editorId" :id="editorId" class="editor"/>
    </div>
    <div v-if="getSettings"></div>
  </v-sheet>

</template>

<script>
import EditorJS from "@editorjs/editorjs";
import Header from "@editorjs/header";
import Paragraph from "@editorjs/paragraph";
import List from "@editorjs/list";
import {makeid} from "../../../../Utils/Helpers";

const EVENT_NAME = 'editorChange'.toLowerCase();

let editorSettings = function(component){
  return {
    holder: component.editorId,
    autofocus: true,
    defaultBlock: "paragraph",
    config: {
      minHeight: 30
    },
    tools: {
      header: {
        class: Header,
        shortcut: "CMD+SHIFT+H"
      },
      list: {class: List},
      paragraph: {
        class: Paragraph,
        config: {placeholder: "."}
      }
    },
    data: {
      blocks: [{
        type: "paragraph",
        data: {text: ""}
      }]
    },
    readOnly: component.readonly,
    onChange: function (e) {
      component.save();
    },
    placeholder: component.placeholder
  }
}

export default {
  name: "ControlEditor",
  props: {
    editor: { default: ''},
    error: { default: ''},
    data: {default: ''},
    placeholder: { default: 'Let`s write an awesome story!' },
    readonly: { default: false }
  },
  model: { prop: 'editor', event: EVENT_NAME },
  computed: {
    value: {
      get: function() {
        return this.editor;
      },
      set: function(value) {
        this.$emit(EVENT_NAME, value)
      }
    },
    editorId: function() {
      return makeid(10);
    },
    getSettings: function () {
      // console.log(this.value);
      this.settings = editorSettings(this);
      if(typeof this.value === 'string') {
        this.settings.data.blocks[0].data.text = this.value;
      } else {
        this.settings.data = this.value
      }

      // this.editorJs = new EditorJS(this.settings);
      return this.settings;
    }
  },
  data() {
    return {
      editorJs: {},
      settings: {}
    }
  },
  methods: {
    save: function() {
      this.editorJs.save().then(savedData => {this.value = savedData});
    },
    initEditor: function() {
      this.editorJs = new EditorJS(this.getSettings);
    }
  },
  mounted: function() {
    this.initEditor();
    // console.log(editorTags);
  },
  beforeUpdate: function () {

  }
}
</script>
<style>
.codex-editor__redactor {
  padding-bottom: 15px !important;
  padding-right: 0 !important;
  margin: 10px !important;
}
</style>