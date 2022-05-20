<template>
  <div class="container-fluid row justify-content-center">
    <div class="modal fade" id="modalCreateAnalysis" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title" v-if="analysisModal.id = ''">Nuevo analisis</h4>
            <h4 class="modal-title" v-else-if="analysisEditable">Editar analisis</h4>
            <h4 class="modal-title" v-else>Ver analisis</h4>

            <div class="float-right">
              <button
                class="btn btn-outline-primary textoTable"
                v-if="!analysisModal.finalizado"
                data-dismiss="modal"
                @click="guardarCambiosAnalisis(true)"
              >Finalizar análisis</button>
            </div>
          </div>
          <div class="modal-body col-md-12">
            <p class="textoTable">
              <b>Tareas</b>
            </p>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 20%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 30%">Nombre</th>
                  <th class="textoCabeceraTable" style="width: 25%">Valor análisis</th>
                  <th class="textoCabeceraTable" style="width: 25%">Razón desperdicio</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(task, index) in analysisModal.tareasAnalisis" :key="task.tarea.id">
                  <td>
                    <div class="row pocoMargenBajo">
                      <button
                        class="btn sinBorde"
                        v-if="analysisEditable"
                        @click="tareaEditable(index)"
                      >
                        <i class="fa fa-pencil fa-lg"></i>
                      </button>
                      <button
                        class="btn sinBorde"
                        v-if="task.analisis.value == 3 && !task.editable"
                        data-toggle="modal"
                        data-target="#modalCreateIssueGeneral"
                        @click="clearDataIncidence(task)"
                      >
                        <i class="fa fa-exclamation-triangle fa-lg"></i>
                      </button>
                    </div>
                    <div class="row justify-content-center" v-if="task.editable">
                      <button
                        class="btn btn-primary col-md-5 pocoMargenDerecha textoTable"
                        @click="confirmarTarea(index)"
                      >Confirmar</button>
                      <button
                        class="btn btn-outline-danger col-md-5 textoTable"
                        @click="cancelarTarea(index)"
                      >Cancelar</button>
                    </div>
                  </td>
                  <td class="textoTable" v-if="task.analisis.name != ''">{{ task.analisis.name }}</td>
                  <td class="textoTable" v-else>{{ task.tarea.name }}</td>

                  <td v-if="task.editable">
                    <select class="row form-control pocoMargenBajo textoTable" v-model="task.analisis.value">
                      <option value="1">Valor añadido</option>
                      <option value="2">Valor añadido para negocio</option>
                      <option value="3">Sin valor añadido</option>
                    </select>

                    <div class="row pocoMargenBajo" v-if="task.analisis.value > 0">
                      <label class="textoTable">
                        <b>Razón</b>
                      </label>
                      <textarea
                        class="form-control col-md-12 textoTable"
                        placeholder="Razón"
                        v-model="task.analisis.reason"
                        rows="4"
                      ></textarea>
                    </div>
                  </td>
                  <td v-else-if="task.analisis.value != ''">
                    <p v-if="task.analisis.value == 1" class="pocoMargenBajo textoTable">Valor añadido</p>
                    <p
                      v-else-if="task.analisis.value == 2"
                      class="pocoMargenBajo textoTable"
                    >Valor añadido para negocio</p>
                    <p v-else class="pocoMargenBajo textoTable">Sin valor añadido</p>

                    <div v-if="task.analisis.reason != null && task.analisis.reason != ''">
                      <p class="textoTable">
                        <b>Razón</b>
                      </p>
                      <p class="textoTable" style="white-space: pre-line;">{{ task.analisis.reason }}</p>
                    </div>
                  </td>
                  <td v-else><p class="textoTable">--</p></td>
                  <td>
                    <ul class="list-group pocoMargenBajo" v-if="task.analisis.value == 3">
                      <li
                        class="list-group-item d-flex justify-content-between align-items-center"
                        v-for="(waste, indexWaste) in task.analisis.wastes"
                        :key="indexWaste"
                      >
                        <p class="textoTable" v-if="waste.type != null && waste.type == 1">Defecto</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 2">Espera</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 3">Talento no utilizado</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 4">Transporte</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 5">Inventario</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 6">Movimiento</p>
                        <p class="textoTable" v-else-if="waste.type != null && waste.type == 7">Procesamiento adicional</p>
                        <p class="textoTable" v-else-if="waste.name != null">{{ waste.name }}</p>

                        <button
                          class="btn sinBorde"
                          v-if="task.editable && waste.id > 0"
                          @click="eliminarWaste(index, indexWaste)"
                        >
                          <i class="fa fa-trash fa-lg"></i>
                        </button>
                      </li>
                    </ul>
                    <button
                      v-if="task.editable && task.analisis.value == 3 && !task.newWaste && task.analisis.id > 0"
                      class="btn btn-outline-primary textoTable"
                      @click="nuevoWaste(index)"
                    >Crear nueva</button>

                    <label class="textoTable" v-if="task.editable && task.newWaste">
                      <b>Tipo razón desperdicio</b>
                    </label>
                    <select
                      class="form-control pocoMargenBajo textoTable"
                      v-if="task.editable && task.newWaste"
                      @change="cambiarTipoWaste(index)"
                      v-model="task.analisis.typeWaste"
                    >
                      <option value="1">Predefinida</option>
                      <option value="2">Personalizada</option>
                    </select>

                    <label class="textoTable" v-if="task.editable && task.newWaste">
                      <b>Razón desperdicio</b>
                    </label>
                    <select
                      class="form-control pocoMargenBajo textoTable"
                      v-if="task.analisis.typeWaste == 1 && task.editable && task.newWaste"
                      v-model="task.analisis.wasteTypeValue"
                    >
                      <option value="1">Defecto</option>
                      <option value="2">Espera</option>
                      <option value="3">Talento no utilizado</option>
                      <option value="4">Transporte</option>
                      <option value="5">Inventario</option>
                      <option value="6">Movimiento</option>
                      <option value="7">Procesamiento adicional</option>
                    </select>
                    <input
                      type="text"
                      class="form-control pocoMargenBajo textoTable"
                      placeholder="Razón despercicio"
                      v-if="task.analisis.typeWaste == 2 && task.editable && task.newWaste"
                      v-model="task.analisis.wasteTextValue"
                    />

                    <button
                      class="btn btn-primary pocoMargenDerecha textoTable"
                      v-if="task.editable && task.newWaste"
                      @click="confirmarWaste(index)"
                    >Confirmar</button>
                    <button
                      class="btn btn-outline-danger textoTable"
                      v-if="task.editable && task.newWaste"
                      @click="cancelarWaste(index)"
                    >Cancelar</button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              v-if="analysisEditable"
              data-dismiss="modal"
              @click="guardarCambiosAnalisis(false)"
            >Guardar cambios</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCreateIssueGeneral" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Nueva incidencia</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Proceso</b>
              </label>
              <p class="textoTable">{{ incidenceModal.process.name }}</p>
            </div>

            <div class="form-group">
              <label class="textoTable">
                <b>Nombre</b>
              </label>
              <input
                type="text"
                class="form-control textoTable"
                placeholder="Nombre"
                v-model="incidenceModal.name"
              />
            </div>
            <div class="form-group">
              <label class="textoTable">
                <b>Descripción</b>
              </label>
              <textarea
                class="form-control textoTable"
                placeholder="Descripción"
                v-model="incidenceModal.description"
                rows="4"
              ></textarea>
            </div>

            <label class="textoTable">
              <b>Tareas afectadas</b>
            </label>
            <multiselect
              v-model="incidenceModal.selected"
              :options="tasksProcess"
              :multiple="true"
              placeholder="Tareas"
              :close-on-select="true"
              label="name"
              track-by="name"
              :show-labels="false"
              class="pocoMargenBajo textoTable"
            ></multiselect>

            <p class="textoTable" v-if="imageRecortar != null || incidenceModal.recorte != ''">
              <b>Imagen de la parte afectada</b>
            </p>
            <button
              class="btn btn-secondary pocoMargenBajo textoTable"
              @click="abrirEditor()"
              data-toggle="modal"
              data-target="#modalRecortar"
              v-if="imageRecortar != null"
            >Nuevo recorte</button>
            <img
              class="unaLinea pocoMargenBajo"
              :src="incidenceModal.recorte"
              v-if="incidenceModal.recorte != ''"
            />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              data-dismiss="modal"
              @click="confirmarIncidencia()"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalRecortar" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Recorte de imagen del proceso</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="row justify-content-center pocoMargenBajo" id="editor"></div>

            <h3 class="row justify-content-center">Resultado</h3>
            <div class="row justify-content-center">
              <canvas id="preview"></canvas>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              data-dismiss="modal"
              @click="confirmarRecorte()"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <div class="row justify-content-between">
        <h1 class="col-md-9 pocoMargenBajo">Análisis de valor añadido de {{ process.name }}</h1>

        <div class="col-md-3">
          <div class="row justify-content-end">
            <button
              class="btn btn-primary btn-lg pocoMargenDerecha"
              @click="clearDataAnalysis()"
              data-toggle="modal"
              data-target="#modalCreateAnalysis"
            >Iniciar nuevo análisis</button>
            <button class="btn btn-outline-dark btn-lg" @click="volverProcesos()">Volver</button>
          </div>
        </div>
      </div>
      <div class="row">
        <table class="table table-light table-striped">
          <thead class="thead-dark">
            <tr>
              <th class="textoCabeceraTable" style="width: 20%">Acciones</th>
              <th class="textoCabeceraTable" style="width: 25%">Fecha inicio</th>
              <th class="textoCabeceraTable" style="width: 25%">Fecha finalización</th>
              <th class="textoCabeceraTable" style="width: 30%">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(a, index) in analysis" :key="a.id">
              <td>
                <div class="row">
                  <button
                    class="btn sinBorde"
                    @click="assignDataAnalysis(index, false)"
                    data-toggle="modal"
                    data-target="#modalCreateAnalysis"
                  >
                    <i class="fa fa-search fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    @click="assignDataAnalysis(index, true)"
                    v-if="!a.finalizado"
                    data-toggle="modal"
                    data-target="#modalCreateAnalysis"
                  >
                    <i class="fa fa-pencil fa-lg"></i>
                  </button>
                </div>
              </td>
              <td><p class="textoTable">{{ fechaFormateada(a.created_at) }}</p></td>
              <td v-if="a.finalizado"><p class="textoTable">{{ fechaFormateada(a.updated_at) }}</p></td>
              <td v-else><p class="textoTable">--</p></td>
              <td>
                <p class="textoTable" v-if="a.finalizado">Finalizado</p>
                <p class="textoTable" v-else>En curso</p>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
</template>

<script>
import Multiselect from "vue-multiselect";

function appendLeadingZeroes(n) {
  if (n <= 9) {
    return "0" + n;
  }
  return n;
}

export default {
  props: ["usuario", "proceso", "tareas", "analisis"],

  components: {
    Multiselect,
  },

  data() {
    return {
      user: {},
      process: {},
      tasks: [],
      analysis: [],
      analysisModal: {
        id: "",
        finalizado: false,
        index: -1,
        tareasAnalisis: [],
      },
      analysisEditable: false,
      incidenceModal: {
        id: "",
        name: "",
        description: "",
        process: {},
        selected: "",
        state: 1,
        type: 1,
        recorte: "",
      },
      tasksProcess: [],
      imageRecortar: "",
    };
  },

  methods: {
    cambiarTipoWaste(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];
      Vue.set(this.$data.analysisModal.tareasAnalisis, index, tarea);
    },

    nuevoWaste(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      tarea.newWaste = true;
    },
    confirmarWaste(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      if (
        tarea.analisis.typeWaste == 1 ||
        (tarea.analisis.typeWaste == 2 && tarea.analisis.wasteTextValue != "")
      ) {
        let waste = {
          task_analysis_id: tarea.analisis.id,
        };

        if (tarea.analisis.typeWaste == 1) {
          waste.type = tarea.analisis.wasteTypeValue;
        } else if (tarea.analisis.typeWaste == 2) {
          waste.name = tarea.analisis.wasteTextValue;
        }

        tarea.analisis.newWastes.push(waste);
        tarea.analisis.wastes.push(waste);
      }

      tarea.newWaste = false;
    },
    cancelarWaste(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      tarea.analisis.typeWaste = 1;
      tarea.analisis.wasteTypeValue = 1;
      tarea.analisis.wasteTextValue = "";
      tarea.newWaste = false;
    },
    eliminarWaste(indexTask, indexWaste) {
      let waste = this.$data.analysisModal.tareasAnalisis[indexTask].analisis
        .wastes[indexWaste];

      this.$data.analysisModal.tareasAnalisis[
        indexTask
      ].analisis.deleteWastes.push(waste);

      this.$data.analysisModal.tareasAnalisis[indexTask].analisis.wastes.splice(
        indexWaste,
        1
      );
    },

    abrirEditor() {
      let editor = document.querySelector("#editor");

      // Borra editor en caso que existiera una imagen previa
      editor.innerHTML = "";
      let cropprImg = document.createElement("img");
      cropprImg.setAttribute("id", "croppr");
      editor.appendChild(cropprImg);

      let miCanvas = document.querySelector("#preview");
      // Contexto del canvas
      let contexto = miCanvas.getContext("2d");
      contexto.clearRect(0, 0, miCanvas.width, miCanvas.height);

      // Envia la imagen al editor para su recorte
      document
        .querySelector("#croppr")
        .setAttribute("src", this.$data.imageRecortar);

      let _this = this;

      // Crea el editor
      new Croppr("#croppr", {
        startSize: [1000, 1000],
        onCropEnd: _this.recortarImagen,
      });
    },
    recortarImagen(data) {
      // Variables
      let inicioX = data.x;
      let inicioY = data.y;
      let nuevoAncho = data.width;
      let nuevaAltura = data.height;
      let zoom = 1;

      let _this = this;

      this.$data.image64 = "";

      let miCanvas = document.querySelector("#preview");
      // Contexto del canvas
      let contexto = miCanvas.getContext("2d");

      // La imprimo
      miCanvas.width = nuevoAncho;
      miCanvas.height = nuevaAltura;
      // La declaro
      let miNuevaImagenTemp = new Image();
      // Cuando la imagen se carge se procederá al recorte
      miNuevaImagenTemp.onload = function () {
        // Se recorta
        contexto.drawImage(
          miNuevaImagenTemp,
          inicioX,
          inicioY,
          nuevoAncho * zoom,
          nuevaAltura * zoom,
          0,
          0,
          nuevoAncho,
          nuevaAltura
        );
        // Se transforma a base64
        _this.$data.image64 = miCanvas.toDataURL("image/jpeg");
      };
      // Proporciona la imagen cruda, sin editarla por ahora
      miNuevaImagenTemp.src = this.$data.imageRecortar;
    },

    confirmarRecorte() {
      this.$data.incidenceModal.recorte = this.$data.image64;
    },
    clearDataIncidence(task) {
      this.$data.incidenceModal.id = "";
      this.$data.incidenceModal.name = "";
      this.$data.incidenceModal.description = "";
      this.$data.incidenceModal.selected = [];
      this.$data.incidenceModal.state = 1;
      this.$data.incidenceModal.type = 1;
      this.$data.incidenceModal.recorte = "";

      let proceso = this.$data.process;

      this.$data.incidenceModal.process = proceso;

      axios
        .get("/documentations/process/" + proceso.id + "/cut")
        .then((response) => {
          this.$data.imageRecortar = response.data.file;
        });

      axios.get("/tasks/process/" + proceso.id).then((response) => {
        this.$data.tasksProcess = response.data;

        if (task.tarea.id != null) {
          this.$data.tasksProcess.forEach((element) => {
            if (element.id == task.tarea.id) {
              this.$data.incidenceModal.selected.push(element);
            }
          });
        }
      });
    },
    confirmarIncidencia() {
      let data = {
        id: this.$data.incidenceModal.id,
        name: this.$data.incidenceModal.name,
        description: this.$data.incidenceModal.description,
        processId: this.$data.incidenceModal.process.id,
        type: 1,
        state: this.$data.incidenceModal.state,
        recorte: this.$data.incidenceModal.recorte,
        creado: this.$data.user.id,
        modificado: this.$data.user.id,
      };

      axios
        .post("/issues", data)
        .then((response) => {
          let idIssue = parseInt(response.data);

          let tareas = this.$data.incidenceModal.selected;

          tareas.forEach((element) => {
            let issueTask = {
              issueId: idIssue,
              taskId: element.id,
            };

            axios
              .post("/issues/tasks", issueTask)
              .then((response) => {})
              .catch((error) => {
                console.log(error.response);
              });
          });

          axios.get("/users/process/" + data.processId).then((response) => {
            let responsables = response.data;

            responsables.forEach((element) => {
              let notification = {
                message: "Se ha creado la incidencia " + data.name,
                typeElement: 4,
                idElement: idIssue,
                user_id: element.id,
              };

              axios
                .post("/notifications", notification)
                .then((response) => {})
                .catch((error) => {
                  console.log(error.response);
                });
            });
          });
        })
        .catch((error) => {
          console.log(error.response);
        });
    },
    volverProcesos() {
      window.location.href = "/processes";
    },
    clearDataAnalysis() {
      this.$data.analysisModal.id = "";
      this.$data.analysisModal.finalizado = false;
      this.$data.analysisModal.index = -1;

      this.$data.analysisModal.tareasAnalisis = [];
      this.$data.tasks.forEach((element) => {
        let tareaAnalisis = {
          tarea: element,
          analisis: {
            id: "",
            name: "",
            process_id: this.$data.process.id,
            value: "",
            task_id: element.id,
            wastes: [],
            newWastes: [],
            deleteWastes: [],
            typeWaste: 1,
            wasteTypeValue: 1,
            wasteTextValue: "",
          },
          newWaste: false,
        };

        this.$data.analysisModal.tareasAnalisis.push(tareaAnalisis);
      });

      this.$data.analysisEditable = true;
    },

    assignDataAnalysis(index, editable) {
      let analisis = this.$data.analysis[index];

      this.$data.analysisModal.id = parseInt(analisis.id);
      this.$data.analysisModal.finalizado = analisis.finalizado;
      this.$data.analysisModal.index = index;

      this.$data.analysisEditable = editable;

      this.$data.analysisModal.tareasAnalisis = [];
      this.$data.tasks.forEach((element) => {
        let tareaAnalisis = {
          tarea: element,
          analisis: {
            id: "",
            name: "",
            process_id: this.$data.process.id,
            value: "",
            task_id: element.id,
            reason: "",
            wastes: [],
            newWastes: [],
            deleteWastes: [],
          },
          newWaste: false,
        };

        this.$data.analysisModal.tareasAnalisis.push(tareaAnalisis);
      });

      axios.get("/analysis/" + analisis.id + "/tasks").then((response) => {
        let tareasAnalisis = response.data;

        tareasAnalisis.forEach((elementAnalisis) => {
          let aparece = false;

          axios.get("/analysis/wastes/" + elementAnalisis.id).then((res) => {
            let wastes = res.data;

            this.$data.analysisModal.tareasAnalisis.forEach((elementTarea) => {
              if (elementAnalisis.task_id == elementTarea.tarea.id) {
                aparece = true;

                elementTarea.analisis.id = elementAnalisis.id;
                elementTarea.analisis.name = elementAnalisis.name;
                elementTarea.analisis.process_id = elementAnalisis.process_id;
                elementTarea.analisis.value = elementAnalisis.value;
                elementTarea.analisis.task_id = elementAnalisis.task_id;
                elementTarea.analisis.reason = elementAnalisis.reason;
                elementTarea.analisis.wastes = wastes;
                elementTarea.analisis.newWastes = [];
                elementTarea.analisis.deleteWastes = [];
                elementTarea.analisis.typeWaste = 1;
                elementTarea.analisis.wasteTypeValue = 1;
                elementTarea.analisis.wasteTextValue = "";
              }
            });

            if (!aparece) {
              let tareaAnalisis = {
                tarea: {},
                analisis: {
                  id: elementAnalisis.id,
                  name: elementAnalisis.name,
                  process_id: elementAnalisis.process_id,
                  value: elementAnalisis.value,
                  task_id: elementAnalisis.task_id,
                  reason: elementAnalisis.reason,
                  wastes: wastes,
                  newWastes: [],
                  deleteWastes: [],
                  typeWaste: 1,
                  wasteTypeValue: 1,
                  wasteTextValue: "",
                },
                newWaste: false,
              };

              this.$data.analysisModal.tareasAnalisis.push(tareaAnalisis);
            }
          });
        });
      });
    },

    tareaEditable(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      tarea.editable = true;
      tarea.analisis.oldvalue = tarea.analisis.value;
      tarea.analisis.oldvaluereason = tarea.analisis.reason;

      Vue.set(this.$data.analysisModal.tareasAnalisis, index, tarea);
    },

    confirmarTarea(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      tarea.editable = false;
      tarea.editada = true;

      Vue.set(this.$data.analysisModal.tareasAnalisis, index, tarea);
    },

    cancelarTarea(index) {
      let tarea = this.$data.analysisModal.tareasAnalisis[index];

      tarea.editable = false;
      tarea.analisis.value = tarea.analisis.oldvalue;
      tarea.analisis.reason = tarea.analisis.oldvaluereason;

      Vue.set(this.$data.analysisModal.tareasAnalisis, index, tarea);
    },

    guardarCambiosAnalisis(finalizar) {
      if (this.$data.analysisModal.index == -1) {
        let data = {
          process_id: this.$data.process.id,
          finalizado: finalizar,
        };

        axios
          .post("/analysis", data)
          .then((response) => {
            let analisis = response.data;

            this.$data.analysis.push(analisis);

            this.$data.analysisModal.tareasAnalisis.forEach((element) => {
              if (element.editada) {
                console.log(element);

                if (element.analisis.id > 0) {
                  let dataTask = {
                    value: element.analisis.value,
                    reason: element.analisis.reason,
                  };

                  axios
                    .put("/analysis/task/" + element.analisis.id, dataTask)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                } else {
                  let dataTask = {
                    name:
                      element.analisis.name != ""
                        ? element.analisis.name
                        : element.tarea.name,
                    value_added_analysis_id: analisis.id,
                    task_id:
                      element.analisis.task_id != ""
                        ? element.analisis.task_id
                        : element.tarea.id,
                    value: element.analisis.value,
                    reason: element.analisis.reason,
                  };

                  axios
                    .post("/analysis/task", dataTask)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
              }
            });

            axios
              .get("/users/process/" + this.$data.process.id)
              .then((response) => {
                let responsables = response.data.filter(
                  (u) => u.role == 2 || u.role == 3
                );

                responsables.forEach((element) => {
                  let notification = {
                    message:
                      "Se ha creado un análisis de valor del proceso " +
                      this.$data.process.name,
                    typeElement: 3,
                    idElement: analisis.id,
                    user_id: element.id,
                  };

                  axios
                    .post("/notifications", notification)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                });
              });
          })
          .catch((error) => {
            console.log(error.response);
          });
      } else {
        let analisis = this.$data.analysis[this.$data.analysisModal.index];

        if (finalizar) {
          let data = {
            finalizado: finalizar,
          };

          axios
            .put("/analysis/" + analisis.id, data)
            .then((response) => {
              analisis.finalizado = finalizar;

              Vue.set(
                this.$data.analysis,
                this.$data.analysisModal.index,
                analisis
              );
            })
            .catch((error) => {
              console.log(error.response);
            });

          axios
            .get("/users/process/" + this.$data.process.id)
            .then((response) => {
              let responsables = response.data.filter(
                (u) => u.role == 2 || u.role == 3
              );

              responsables.forEach((element) => {
                let notification = {
                  message:
                    "Se ha finalizado un análisis de valor del proceso " +
                    this.$data.process.name,
                  typeElement: 3,
                  idElement: analisis.id,
                  user_id: element.id,
                };

                axios
                  .post("/notifications", notification)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            });
        } else {
          axios
            .get("/users/process/" + this.$data.process.id)
            .then((response) => {
              let responsables = response.data.filter(
                (u) => u.role == 2 || u.role == 3
              );

              responsables.forEach((element) => {
                let notification = {
                  message:
                    "Se ha modificado un análisis de valor del proceso " +
                    this.$data.process.name,
                  typeElement: 3,
                  idElement: analisis.id,
                  user_id: element.id,
                };

                axios
                  .post("/notifications", notification)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            });
        }

        this.$data.analysisModal.tareasAnalisis.forEach((element) => {
          if (element.editada) {
            if (element.analisis.id > 0) {
              let dataTask = {
                value: element.analisis.value,
                reason: element.analisis.reason,
              };

              axios
                .put("/analysis/task/" + element.analisis.id, dataTask)
                .then((response) => {})
                .catch((error) => {
                  console.log(error.response);
                });

              element.analisis.newWastes.forEach((e) => {
                axios
                  .post("/analysis/wastes", e)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              element.analisis.deleteWastes.forEach((e) => {
                axios
                  .delete("/analysis/wastes/" + e.id)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            } else {
              let dataTask = {
                name:
                  element.analisis.name != ""
                    ? element.analisis.name
                    : element.tarea.name,
                value_added_analysis_id: analisis.id,
                task_id:
                  element.analisis.task_id != ""
                    ? element.analisis.task_id
                    : element.tarea.id,
                value: element.analisis.value,
                reason: element.analisis.reason,
              };

              axios
                .post("/analysis/task", dataTask)
                .then((response) => {})
                .catch((error) => {
                  console.log(error.response);
                });

              element.analisis.newWastes.forEach((e) => {
                axios
                  .post("/analysis/wastes", e)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              element.analisis.deleteWastes.forEach((e) => {
                axios
                  .delete("/analysis/wastes/" + e.id)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            }
          }
        });
      }
    },

    fechaFormateada(fecha) {
      let date = new Date(fecha);
      let format =
        appendLeadingZeroes(date.getDate()) +
        "/" +
        appendLeadingZeroes(date.getMonth() + 1) +
        "/" +
        date.getFullYear() +
        " " +
        appendLeadingZeroes(date.getHours()) +
        ":" +
        appendLeadingZeroes(date.getMinutes());

      return format;
    },
  },

  mounted() {
    this.user = this.usuario;
    this.process = this.proceso;
    this.tasks = this.tareas;
    this.analysis = this.analisis;
  },
};
</script>

<style>
</style>
