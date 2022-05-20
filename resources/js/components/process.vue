<template>
  <div class="container-fluid row justify-content-center">
    <div class="col-md-10">
      <div class="row justify-content-between">
        <h1 class="col-md-4 pocoMargenBajo">{{ process.name }}</h1>

        <div class="col-md-8">
          <div class="row justify-content-end">
            <button
              class="btn btn-primary btn-lg pocoMargenDerecha"
              v-if="editable"
              @click="confirmarCambios()"
            >Confirmar cambios</button>
            <button
              class="btn btn-outline-danger btn-lg pocoMargenDerecha"
              v-if="editable"
              @click="cancelarEdicion()"
            >Cancelar</button>
            <button
              class="btn btn-warning btn-lg pocoMargenDerecha"
              v-if="!editable && (user.role == 2 || tienePermiso('editar') == true)"
              @click="activarEdicion()"
            >Editar</button>
            <button
              class="btn btn-danger btn-lg pocoMargenDerecha"
              v-if="!editable && (user.role == 2 || tienePermiso('eliminar') == true)"
              @click="eliminarProceso()"
            >Eliminar</button>
            <button
              class="btn btn-outline-dark btn-lg"
              v-if="!editable"
              @click="volverProcesos()"
            >Volver</button>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-7">
          <tasks :usuario="user" :tareas="tareas"></tasks>
        </div>
        <div class="col-md-5">
          <documents :usuario="user" :documentos="documentos"></documents>

          <div class="pocoMargenArriba">
            <objectives :usuario="user" :objetivos="objetivos"></objectives>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import tasks from "./tasks";
import documents from "./documents";
import objectives from "./objectives";

export default {
  props: ["usuario", "proceso", "tareas", "documentos", "objetivos", "permisos"],

  components: {
    tasks,
    documents,
    objectives,
  },

  data() {
    return {
      user: {},
      process: {},
      editable: false,
      deleteDocuments: [],
      deleteTasks: [],
      editTasks: [],
      deleteObjectives: [],
      editObjectives: [],
      permits: []
    };
  },

  methods: {
      tienePermiso(accion) {
      let process = this.$data.process;

      switch (accion) {
        case "editar":
          return this.$data.permits.filter(p => p.id == process.id && p.role == 2).length > 0

          break;

        case "eliminar":
          return this.$data.permits.filter(p => p.id == process.id && p.role == 2).length > 0

          break;

        default:
          break;
      }
    },
    volverProcesos() {
      window.location.href = "/processes";
    },
    cancelarEdicion() {
      location.reload();
    },
    activarEdicion() {
      this.$data.editable = true;

      this.$root.$emit("procesoEditable", true);
    },
    eliminarProceso() {
      Swal.fire({
        title: "¿Está seguro que quiere eliminar el proceso?",
        text: "Se perderá toda la información asociada y no se podrá recuperar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.value) {
          let proceso = this.$data.process;

          axios
            .delete("/processes/" + proceso.id)
            .then((response) => {
              window.location.href = "/processes";
            })
            .catch((error) => {
              console.log(error.response);
            });
        }
      });
    },

    confirmarCambios() {
      let idProceso = this.$data.process.id;

      this.$data.deleteTasks.forEach((element) => {
        let URL = "/tasks/" + element.id;

        axios
          .delete(URL)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      });

      this.$data.editTasks.forEach((element) => {
        let URL = "/tasks/" + element.id;

        let tarea = {
          name: element.name,
          type: element.type,
          processId: idProceso,
        };

        axios
          .put(URL, tarea)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      });

      this.$data.deleteDocuments.forEach((element) => {
        let URL = "/documentations/" + element.id;

        axios
          .delete(URL)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      });

      this.$data.deleteObjectives.forEach((element) => {
        let URL = "/objectives/" + element.id;

        axios
          .delete(URL)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      });

      this.$data.editObjectives.forEach((element) => {
        let URL = "/objectives/" + element.id;

        let objetivo = {
          name: element.name,
          type: element.type,
          valor: element.valor,
          processId: idProceso,
        };

        axios
          .put(URL, objetivo)
          .then((response) => {})
          .catch((error) => {
            console.log(error.response);
          });
      });

      Swal.fire(
        "Guardado correctamente",
        "Toda la información ha sido guardada",
        "success"
      );

      this.$data.editable = false;

      this.$root.$emit("procesoEditable", false);

      this.$data.editTasks = [];
      this.$data.deleteTasks = [];
      this.$data.deleteDocuments = [];
      this.$data.editObjectives = [];
      this.$data.deleteObjectives = [];
    },
  },

  mounted() {
    this.user = this.usuario;
    this.process = this.proceso;
    this.permits = this.permisos;

    this.$root.$on("tareaEditada", (data) => {
      this.$data.editTasks.push(data);
    });

    this.$root.$on("tareaEliminada", (data) => {
      this.$data.deleteTasks.push(data);
    });

    this.$root.$on("documentoEliminado", (data) => {
      this.$data.deleteDocuments.push(data);
    });

    this.$root.$on("objetivoEditado", (data) => {
      this.$data.editObjectives.push(data);
    });

    this.$root.$on("objetivoEliminado", (data) => {
      this.$data.deleteObjectives.push(data);
    });
  },
};
</script>

<style>

</style>
