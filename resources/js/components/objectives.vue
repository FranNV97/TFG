<template>
  <div class="panelDiv apartado">
    <div class="modal fade" id="modalCreateObjective" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ objectiveModal.name }}</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label>
                <b>Nombre</b>
              </label>
              <input
                type="text"
                class="form-control"
                placeholder="Nombre"
                v-model="objectiveModal.name"
              />
            </div>

            <div class="form-group">
              <label>
                <b>Tipo</b>
              </label>
              <select class="form-control" v-model="objectiveModal.type">
                <option value="1">Ventas</option>
                <option value="2">Redes sociales</option>
                <option value="3">Retail</option>
                <option value="4">Logística</option>
                <option value="5">Producción</option>
                <option value="6">Financiero</option>
                <option value="7">Marketing digital</option>
              </select>
            </div>

            <div class="form-group">
              <label>
                <b>Valor</b>
              </label>
              <input
                type="text"
                class="form-control"
                placeholder="Valor"
                v-model="objectiveModal.valor"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary"
              v-on:click="confirmarObjetivo()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <h2>Objetivos</h2>

    <div class="row justify-content-center">
      <div
        class="card col-md-11 pocoMargen"
        v-for="(objective, index) in objectives"
        :key="objective.id"
      >
        <div class="card-body">
          <div class="row justify-content-between">
            <h5
              class="card-title"
              v-if="!editable"
            >
                {{ objective.name }} - {{ objective.valor }}

                <p style="display:inline;" v-if="objective.type == 1"> (Ventas)</p>
                <p style="display:inline;" v-if="objective.type == 2"> (Redes sociales)</p>
                <p style="display:inline;" v-if="objective.type == 3"> (Retail)</p>
                <p style="display:inline;" v-if="objective.type == 4"> (Logística)</p>
                <p style="display:inline;" v-if="objective.type == 5"> (Producción)</p>
                <p style="display:inline;" v-if="objective.type == 6"> (Financiero)</p>
                <p style="display:inline;" v-if="objective.type == 7"> (Marketing digital)</p>
            </h5>
            <h5
              class="card-title col-md-9"
              v-else
            >
                {{ objective.name }} - {{ objective.valor }}

                <p style="display:inline;" v-if="objective.type == 1"> (Ventas)</p>
                <p style="display:inline;" v-if="objective.type == 2"> (Redes sociales)</p>
                <p style="display:inline;" v-if="objective.type == 3"> (Retail)</p>
                <p style="display:inline;" v-if="objective.type == 4"> (Logística)</p>
                <p style="display:inline;" v-if="objective.type == 5"> (Producción)</p>
                <p style="display:inline;" v-if="objective.type == 6"> (Financiero)</p>
                <p style="display:inline;" v-if="objective.type == 7"> (Marketing digital)</p>

            </h5>

            <div class="col-md-3 row justify-content-end" v-if="editable">
              <button
                class="btn sinBorde"
                @click="assignDataObjectives(index)"
                data-toggle="modal"
                data-target="#modalCreateObjective"
              >
                <i class="fa fa-pencil fa-lg"></i>
              </button>

              <button class="btn sinBorde" @click="eliminarObjetivo(index)">
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
  props: ["usuario", "objetivos"],

  data() {
    return {
      user: {},
      objectives: [],
      editable: false,
      objectiveModal: {
        id: "",
        name: "",
        type: "",
        valor: "",
        index: -1,
      },
    };
  },

  methods: {
    assignDataObjectives(index) {
      let objetivo = this.$data.objectives[index];

      this.$data.objectiveModal.id = objetivo.id;
      this.$data.objectiveModal.type = objetivo.type;
      this.$data.objectiveModal.name = objetivo.name;
      this.$data.objectiveModal.valor = objetivo.valor;
      this.$data.objectiveModal.index = index;
    },

    confirmarObjetivo() {
      let data = {
        id: this.$data.objectiveModal.id,
        name: this.$data.objectiveModal.name,
        valor: this.$data.objectiveModal.valor,
        type: this.$data.objectiveModal.type,
      };

      let index = this.$data.objectiveModal.index;

      Vue.set(this.$data.objectives, index, data);
      this.$root.$emit("objetivoEditado", data);
    },

    eliminarObjetivo(index) {
      this.$root.$emit("objetivoEliminado", this.$data.objectives[index]);
      this.$data.objectives.splice(index, 1);
    },
  },

  mounted() {
    this.user = this.usuario;
    this.objectives = this.objetivos;

    this.$root.$on("procesoEditable", (editable) => {
      this.$data.editable = editable;
    });
  },
};
</script>

<style>
</style>
