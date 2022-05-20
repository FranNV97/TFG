<template>
  <div class="panelDiv apartado">
    <div class="modal fade" id="modalCreateTask" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ taskModal.name }}</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label>
                <b>Nombre</b>
              </label>
              <input type="text" class="form-control" placeholder="Nombre" v-model="taskModal.name" />
            </div>

            <div class="form-group">
              <label>
                <b>Tipo</b>
              </label>
              <select class="form-control" v-model="taskModal.type">
                <option value="1">Servicio</option>
                <option value="2">Subproceso</option>
                <option value="3">Recepción</option>
                <option value="4">Envío</option>
                <option value="5">Usuario</option>
                <option value="6">Secuencia de comandos</option>
                <option value="7">Abstracta</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              v-on:click="confirmarTarea()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <h2>Tareas</h2>

    <div class="row justify-content-center">
      <div class="card col-md-5 pocoMargen" v-for="(task, index) in tasks" :key="task.id">
        <div class="card-body">
          <div class="row justify-content-between">
            <h5 v-if="!editable" class="card-title col-md-10">{{ task.name }}</h5>
            <h5 v-else class="card-title col-md-6">{{ task.name }}</h5>

            <div class="col-md-2 row justify-content-end">
              <i class="fa fa-cog" aria-hidden="true" v-if="task.type == 1"></i>
              <i class="fa fa-plus-square" aria-hidden="true" v-else-if="task.type == 2"></i>
              <i class="fa fa-envelope-o" aria-hidden="true" v-else-if="task.type == 3"></i>
              <i class="fa fa-envelope" aria-hidden="true" v-else-if="task.type == 4"></i>
              <i class="fa fa-user" aria-hidden="true" v-else-if="task.type == 5"></i>
              <i class="fa fa-file-text-o" aria-hidden="true" v-else-if="task.type == 6"></i>
            </div>

            <div class="col-md-4 row justify-content-end" v-if="editable">
              <button
                class="btn sinBorde"
                @click="assignDataTask(index)"
                data-toggle="modal"
                data-target="#modalCreateTask"
              >
                <i class="fa fa-pencil fa-lg"></i>
              </button>

              <button class="btn sinBorde" @click="eliminarTarea(index)">
                <i class="fa fa-trash fa-lg"></i>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: ["usuario", "tareas"],

  data() {
    return {
      user: {},
      tasks: [],
      editable: false,
      taskModal: {
        id: "",
        name: "",
        type: 0,
        index: -1,
      },
    };
  },

  methods: {
    assignDataTask(index) {
      let tarea = this.$data.tasks[index];

      this.$data.taskModal.id = tarea.id;
      this.$data.taskModal.name = tarea.name;
      this.$data.taskModal.type = tarea.type;
      this.$data.taskModal.index = index;
    },
    confirmarTarea() {
      if (this.$data.taskModal.name != "") {
        let data = {
          id: this.$data.taskModal.id,
          name: this.$data.taskModal.name,
          type: this.$data.taskModal.type,
        };

        let index = this.$data.taskModal.index;

        Vue.set(this.$data.tasks, index, data);
        this.$root.$emit("tareaEditada", data);
      }
    },
    eliminarTarea(index) {
      this.$root.$emit("tareaEliminada", this.$data.tasks[index]);
      this.$data.tasks.splice(index, 1);
    },
  },

  mounted() {
    this.user = this.usuario;
    this.tasks = this.tareas;

    this.$root.$on("procesoEditable", (editable) => {
      this.$data.editable = editable;
    });
  },
};
</script>

<style>
</style>
