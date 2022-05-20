<template>
  <div class="panelDiv apartado">
    <h2 class="pocoMargenBajo">Documentación</h2>

    <div class="row">
      <div class="col-md-4 centradoTexto" v-for="(doc, index) in documents" :key="doc.id">
        <i
          v-if="esDelTipo(index, 'zip') || esDelTipo(index, 'rar')"
          @click="descargarDocumento(index)"
          class="fa fa-file-archive-o fa-4x"
        ></i>
        <i
          v-else-if="esDelTipo(index, 'doc') || esDelTipo(index, 'docx')"
          @click="descargarDocumento(index)"
          class="fa fa-file-word-o fa-4x"
        ></i>
        <i
          v-else-if="esDelTipo(index, 'png') || esDelTipo(index, 'jpg') || esDelTipo(index, 'jpeg')"
          @click="descargarDocumento(index)"
          class="fa fa-file-image-o fa-4x"
        ></i>
        <i
          v-else-if="esDelTipo(index, 'pdf')"
          @click="descargarDocumento(index)"
          class="fa fa-file-pdf-o fa-4x"
        ></i>
        <i
          v-else-if="esDelTipo(index, 'xls') || esDelTipo(index, 'xlsx')"
          @click="descargarDocumento(index)"
          class="fa fa-file-excel-o fa-4x"
        ></i>
        <i
          v-else-if="esDelTipo(index, 'ppt') || esDelTipo(index, 'pptx')"
          @click="descargarDocumento(index)"
          class="fa fa-file-powerpoint-o fa-4x"
        ></i>
        <i v-else @click="descargarDocumento(index)" class="fa fa-file-o fa-5x"></i>
        <p class="textoTable">
          {{ doc.name }}
          <i class="fa fa-trash fa-lg" v-if="editable" @click="eliminarDocumento(index)"></i>
        </p>
      </div>
    </div>
  </div>
</template>

<script>
function dataURItoBlob(dataURI) {
  // convert base64 to raw binary data held in a string
  // doesn't handle URLEncoded DataURIs - see SO answer #6850276 for code that does this
  var byteString = atob(dataURI.split(",")[1]);

  // separate out the mime component
  var mimeString = dataURI.split(",")[0].split(":")[1].split(";")[0];

  // write the bytes of the string to an ArrayBuffer
  var ab = new ArrayBuffer(byteString.length);

  // create a view into the buffer
  var ia = new Uint8Array(ab);

  // set the bytes of the buffer to the correct values
  for (var i = 0; i < byteString.length; i++) {
    ia[i] = byteString.charCodeAt(i);
  }

  // write the ArrayBuffer to a blob, and you're done
  var blob = new Blob([ab], { type: mimeString });
  return blob;
}

export default {
  props: ["usuario", "documentos"],

  data() {
    return {
      user: {},
      documents: [],
      editable: false,
    };
  },

  methods: {
    esDelTipo(index, extension) {
      let documento = this.$data.documents[index];

      let separado = documento.name.split(".");

      if (separado.length > 1 && separado[1] == extension) {
        return true;
      } else {
        return false;
      }
    },

    descargarDocumento(index) {
      let documento = this.$data.documents[index];

      let link = document.createElement("a");
      link.href = window.URL.createObjectURL(dataURItoBlob(documento.file));
      link.download = documento.name;

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    eliminarDocumento(index) {
      this.$root.$emit("documentoEliminado", this.$data.documents[index]);
      this.$data.documents.splice(index, 1);
    },
  },

  mounted() {
    this.user = this.usuario;
    this.documents = this.documentos;

    this.$root.$on("procesoEditable", (editable) => {
      this.$data.editable = editable;
    });
  },
};
</script>

<style>
</style>
