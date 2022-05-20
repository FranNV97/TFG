<template>
  <div class="container-fluid row justify-content-center">
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
              class="btn btn-secondary textoTable pocoMargenBajo"
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

    <div class="modal fade" id="modalCreateProcess" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ processModal.name }}</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Nombre</b>
              </label>
              <input
                type="text"
                class="form-control textoTable"
                placeholder="Nombre"
                v-model="processModal.name"
              />
            </div>
            <div class="form-group">
              <label class="textoTable">
                <b>Descripción</b>
              </label>
              <textarea
                class="form-control textoTable"
                placeholder="Descripción"
                v-model="processModal.description"
                rows="4"
              ></textarea>
            </div>

            <div class="pocoMargenBajo">
              <label class="textoTable">
                <b>Tareas</b>
              </label>
              <button
                style="float: right;"
                class="btn btn-outline-secondary textoTable"
                v-on:click="clearDataTask()"
                data-toggle="modal"
                data-target="#modalCreateTask"
              >Nueva tarea</button>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 15%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 60%">Nombre</th>
                  <th class="textoCabeceraTable" style="width: 20%">Tipo</th>
                  <th class="textoCabeceraTable" style="width: 5%"></th>
                </tr>
              </thead>

              <draggable :options="{animation:200}" :element="'tbody'" @sort="onDropTask">
                <tr v-for="(task, index) in tasksProcessModal" :key="index">
                  <td>
                    <button
                      class="btn sinBorde"
                      v-on:click="assignDataTask(index)"
                      data-toggle="modal"
                      data-target="#modalCreateTask"
                    >
                      <i class="fa fa-pencil fa-lg"></i>
                    </button>
                    <button class="btn sinBorde" v-on:click="eliminarTarea(index)">
                      <i class="fa fa-trash fa-lg"></i>
                    </button>
                  </td>
                  <td><p class="textoTable">{{ task.name }}</p></td>
                  <td>
                    <p class="textoTable" v-if="task.type == 1">Servicio</p>
                    <p class="textoTable" v-else-if="task.type == 2">Subproceso</p>
                    <p class="textoTable" v-else-if="task.type == 3">Recepción</p>
                    <p class="textoTable" v-else-if="task.type == 4">Envío</p>
                    <p class="textoTable" v-else-if="task.type == 5">Usuario</p>
                    <p class="textoTable" v-else-if="task.type == 6">Secuencia de comandos</p>
                    <p class="textoTable" v-else-if="task.type == 7">Abstracta</p>
                    <p class="textoTable" v-else>--</p>
                  </td>
                  <td>
                    <i class="fa fa-bars fa-lg" aria-hidden="true"></i>
                  </td>
                </tr>
              </draggable>
            </table>

            <div class="pocoMargenBajo">
              <label class="textoTable">
                <b>Documentación</b>
              </label>
              <button
                style="float: right;"
                class="btn btn-outline-secondary textoTable"
                v-on:click="clearDataDocument()"
                data-toggle="modal"
                data-target="#modalCreateDocument"
              >Añadir documento</button>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 15%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 60%">Nombre</th>
                  <th class="textoCabeceraTable" style="width: 25%">Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(doc, index) in documentationsProcessModal" :key="doc.id">
                  <td>
                    <button v-on:click="descargarDocumento(index)" class="btn sinBorde">
                      <i class="fa fa-download fa-lg"></i>
                    </button>
                    <button class="btn sinBorde" v-on:click="eliminarDocumento(index)">
                      <i class="fa fa-trash fa-lg"></i>
                    </button>
                  </td>
                  <td><p class="textoTable">{{ doc.name }}</p></td>
                  <td>
                    <p class="textoTable" v-if="doc.type == 1">General</p>
                    <p class="textoTable" v-else-if="doc.type == 2">Imagen BPMN</p>
                    <p class="textoTable" v-else-if="doc.type == 3">Archivo BPMN</p>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="pocoMargenBajo">
              <label class="textoTable">
                <b>Participantes</b>
              </label>
              <button
                style="float: right;"
                class="btn btn-outline-secondary textoTable"
                v-on:click="clearDataParticipante()"
                data-toggle="modal"
                data-target="#modalCreateParticipante"
              >Nuevo participante</button>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 15%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 35%">Nombre</th>
                  <th class="textoCabeceraTable" style="width: 25%">Correo</th>
                  <th class="textoCabeceraTable" style="width: 25%">Rol</th>
                </tr>
              </thead>
              <tbody>
                <tr
                  v-for="(participante, index) in participantesProcessModal"
                  :key="participante.id"
                >
                  <td>
                    <button class="btn sinBorde" v-on:click="eliminarParticipante(index)">
                      <i class="fa fa-trash fa-lg"></i>
                    </button>
                  </td>
                  <td><p class="textoTable">{{ participante.name }} {{ participante.surname }}</p></td>
                  <td><p class="textoTable">{{ participante.email }}</p></td>
                  <td>
                    <p class="textoTable" v-if="participante.role == 1">Actor</p>
                    <p class="textoTable" v-else-if="participante.role == 2">Responsable</p>
                    <p class="textoTable" v-else-if="participante.role == 3">Análisis</p>
                  </td>
                </tr>
              </tbody>
            </table>

            <div class="pocoMargenBajo">
              <label class="textoTable">
                <b>Objetivos</b>
              </label>
              <button
                style="float: right;"
                class="btn btn-outline-secondary textoTable"
                v-on:click="clearDataObjectives()"
                data-toggle="modal"
                data-target="#modalCreateObjective"
              >Añadir objetivo</button>
            </div>
            <table class="table table-striped">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 15%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 50%">Nombre</th>
                  <th class="textoCabeceraTable" style="width: 15%">Valor</th>
                  <th class="textoCabeceraTable" style="width: 20%">Tipo</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(objetivo, index) in objectivesProcessModal" :key="objetivo.id">
                  <td>
                    <button
                      class="btn sinBorde"
                      v-on:click="assignDataObjectives(index)"
                      data-toggle="modal"
                      data-target="#modalCreateObjective"
                    >
                      <i class="fa fa-pencil fa-lg"></i>
                    </button>
                    <button class="btn sinBorde" v-on:click="eliminarObjetivo(index)">
                      <i class="fa fa-trash fa-lg"></i>
                    </button>
                  </td>
                  <td><p class="textoTable">{{ objetivo.name }}</p></td>
                  <td><p class="textoTable">{{ objetivo.valor }}</p></td>
                  <td>
                    <p class="textoTable" v-if="objetivo.type == 1">Ventas</p>
                    <p class="textoTable" v-else-if="objetivo.type == 2">Redes sociales</p>
                    <p class="textoTable" v-else-if="objetivo.type == 3">Retail</p>
                    <p class="textoTable" v-else-if="objetivo.type == 4">Logística</p>
                    <p class="textoTable" v-else-if="objetivo.type == 5">Producción</p>
                    <p class="textoTable" v-else-if="objetivo.type == 6">Financiero</p>
                    <p class="textoTable" v-else-if="objetivo.type == 7">Marketing digital</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              v-on:click="guardarProceso()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCreateTask" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ taskModal.name }}</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Nombre</b>
              </label>
              <input type="text" class="form-control textoTable" placeholder="Nombre" v-model="taskModal.name" />
            </div>

            <div class="form-group">
              <label class="textoTable">
                <b>Tipo</b>
              </label>
              <select class="form-control textoTable" v-model="taskModal.type">
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
              class="btn btn-primary textoTable"
              v-on:click="confirmarTarea()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCreateDocument" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Nuevo documento</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Documento</b>
              </label>
              <input class="form-control-file" type="file" @change="previewFiles" />
            </div>

            <div class="form-group">
              <label class="textoTable">
                <b>Tipo</b>
              </label>
              <select class="form-control textoTable" v-model="documentModal.type">
                <option value="1">General</option>
                <option value="2">Imagen BPMN</option>
                <option value="3">Archivo BPMN</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              v-on:click="confirmarDocumento()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCreateParticipante" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Nuevo participante</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Usuario</b>
              </label>
              <select class="form-control textoTable" v-model="participanteModal.id">
                <option v-for="user in users" :value="user.id" :key="user.id">{{ user.email }}</option>
              </select>
            </div>

            <div class="form-group">
              <label class="textoTable">
                <b>Rol</b>
              </label>
              <select class="form-control textoTable" v-model="participanteModal.role">
                <option value="1">Actor</option>
                <option value="2">Responsable</option>
                <option value="3">Análisis</option>
              </select>
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              v-on:click="confirmarParticipante()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCreateObjective" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">{{ objectiveModal.name }}</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Nombre</b>
              </label>
              <input
                type="text"
                class="form-control textoTable"
                placeholder="Nombre"
                v-model="objectiveModal.name"
              />
            </div>

            <div class="form-group">
              <label class="textoTable">
                <b>Tipo</b>
              </label>
              <select class="form-control textoTable" v-model="objectiveModal.type">
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
              <label class="textoTable">
                <b>Valor</b>
              </label>
              <input
                type="text"
                class="form-control textoTable"
                placeholder="Valor"
                v-model="objectiveModal.valor"
              />
            </div>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              v-on:click="confirmarObjetivo()"
              data-dismiss="modal"
            >Confirmar</button>
            <button type="button" class="btn btn-default textoTable" data-dismiss="modal">Cerrar</button>
          </div>
        </div>
      </div>
    </div>

    <div class="col-md-10">
      <div class="row justify-content-between">
        <h1>Procesos</h1>
        <button
          class="btn btn-primary btn-lg altoBoton pocoMargenBajo"
          v-on:click="clearDataProcess()"
          data-toggle="modal"
          data-target="#modalCreateProcess"
          v-if="user.role == 2"
        >Crear proceso</button>
      </div>

      <div class="row">
        <table class="table table-light table-striped">
          <thead class="thead-dark">
            <tr>
              <th class="textoCabeceraTable" style="width: 15%">Acciones</th>
              <th class="textoCabeceraTable" style="width: 30%">Nombre</th>
              <th class="textoCabeceraTable" style="width: 55%">Descripción</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(process, index) in processes" :key="process.id">
              <td>
                <div class="row">
                  <button class="btn sinBorde" @click="verProceso(process.id)">
                    <i class="fa fa-search fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    v-on:click="assignDataProcess(index)"
                    data-toggle="modal"
                    data-target="#modalCreateProcess"
                    v-if="user.role == 2 || tienePermiso('editar', index) == true"
                  >
                    <i class="fa fa-pencil fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    @click="verValueAdded(process.id)"
                    v-if="user.role == 2 || tienePermiso('analisis', index) == true"
                  >
                    <i class="fa fa-line-chart fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    @click="clearDataIncidence(index)"
                    data-toggle="modal"
                    data-target="#modalCreateIssueGeneral"
                  >
                    <i class="fa fa-exclamation-triangle fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    @click="eliminarProceso(index)"
                    v-if="user.role == 2 || tienePermiso('eliminar', index) == true"
                  >
                    <i class="fa fa-trash fa-lg"></i>
                  </button>
                </div>
              </td>
              <td><p class="textoTable">{{ process.name }}</p></td>
              <td>
                <p class="textoTable" style="white-space: pre-line;">{{ process.description }}</p>
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
import draggable from "vuedraggable";

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
  props: ["usuario", "permisos"],

  components: {
    Multiselect,
    draggable,
  },

  data() {
    return {
      user: {},
      permits: [],
      processes: [],
      processModal: {
        id: "",
        name: "",
        description: "",
        type: "",
        index: -1,
      },
      tasksProcessModal: [],
      newTasks: [],
      deleteTasks: [],
      taskModal: {
        id: "",
        name: "",
        type: 0,
        index: -1,
      },
      documentationsProcessModal: [],
      newDocuments: [],
      deleteDocuments: [],
      documentModal: {
        id: "",
        name: "",
        file: "",
        type: "",
        index: -1,
      },
      objectivesProcessModal: [],
      newObjectives: [],
      deleteObjectives: [],
      objectiveModal: {
        id: "",
        name: "",
        type: "",
        valor: "",
        index: -1,
      },
      participantesProcessModal: [],
      newParticipantes: [],
      deleteParticipantes: [],
      participanteModal: {
        id: "",
        role: "",
        index: -1,
      },
      users: [],
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
    tienePermiso(accion, index) {
      let process = this.$data.processes[index];

      switch (accion) {
        case "editar":
          return this.$data.permits.filter(p => p.id == process.id && p.role == 2).length > 0

          break;

        case "analisis":

          return this.$data.permits.filter(p => p.id == process.id && (p.role == 2 || p.role == 3)).length > 0

          break;

        case "eliminar":
          return this.$data.permits.filter(p => p.id == process.id && p.role == 2).length > 0

          break;

        default:
          break;
      }
    },
    encontrarTareaOrden(orden) {
      return new Promise((resolve, reject) => {
        this.$data.tasksProcessModal.forEach((element) => {
          if (element.order == orden) {
            resolve(element);
          }
        });
      });
    },
    onDropTask(event) {
      let data = {
        newOrder: event.newIndex + 1,
        oldOrder: event.oldIndex + 1,
      };

      let _this = this;

      let tareaMovida = "";
      let tareasReducirOrder = [];
      let tareasAumentarOrder = [];

      _this.encontrarTareaOrden(data.oldOrder).then((response) => {
        tareaMovida = response;
      });

      if (data.oldOrder < data.newOrder) {
        for (let i = data.oldOrder + 1; i <= data.newOrder; i++) {
          _this.encontrarTareaOrden(i).then((response) => {
            tareasReducirOrder.push(response);

            if (i == data.newOrder) {
              tareasReducirOrder.forEach((element) => {
                element.order = element.order - 1;
              });

              tareaMovida.order = data.newOrder;
            }
          });
        }
      } else {
        for (let i = data.newOrder; i < data.oldOrder; i++) {
          _this.encontrarTareaOrden(i).then((response) => {
            tareasAumentarOrder.push(response);

            if (i == data.oldOrder - 1) {
              tareasAumentarOrder.forEach((element) => {
                element.order = element.order + 1;
              });

              tareaMovida.order = data.newOrder;
            }
          });
        }
      }
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

    clearDataIncidence(index) {
      this.$data.incidenceModal.id = "";
      this.$data.incidenceModal.name = "";
      this.$data.incidenceModal.description = "";
      this.$data.incidenceModal.selected = "";
      this.$data.incidenceModal.state = 1;
      this.$data.incidenceModal.type = 1;
      this.$data.incidenceModal.recorte = "";

      let proceso = this.$data.processes[index];

      this.$data.incidenceModal.process = proceso;

      axios
        .get("/documentations/process/" + proceso.id + "/cut")
        .then((response) => {
          this.$data.imageRecortar = response.data.file;
        });

      axios.get("/tasks/process/" + proceso.id).then((response) => {
        this.$data.tasksProcess = response.data;
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
        modificado: this.$data.user.id
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

    assignDataProcess(index) {
      let proceso = this.$data.processes[index];

      this.$data.processModal.id = proceso.id;
      this.$data.processModal.name = proceso.name;
      this.$data.processModal.description = proceso.description;
      this.$data.processModal.type = proceso.type;
      this.$data.processModal.index = index;

      this.$data.tasksProcessModal = [];
      this.$data.documentationsProcessModal = [];
      this.$data.participantesProcessModal = [];
      this.$data.objectivesProcessModal = [];

      axios.get("/tasks/process/" + proceso.id).then((response) => {
        this.$data.tasksProcessModal = response.data;
      });

      axios.get("/documentations/process/" + proceso.id).then((response) => {
        this.$data.documentationsProcessModal = response.data;
      });

      axios.get("/users/process/" + proceso.id).then((response) => {
        this.$data.participantesProcessModal = response.data;
      });

      axios.get("/objectives/process/" + proceso.id).then((response) => {
        this.$data.objectivesProcessModal = response.data;
      });
    },
    clearDataProcess() {
      this.$data.processModal.id = "";
      this.$data.processModal.name = "";
      this.$data.processModal.description = "";
      this.$data.processModal.type = "";
      this.$data.processModal.index = -1;
      this.$data.deleteTasks = [];
      this.$data.newTasks = [];
      this.$data.deleteDocuments = [];
      this.$data.newDocuments = [];
      this.$data.tasksProcessModal = [];
      this.$data.documentationsProcessModal = [];
      this.$data.participantesProcessModal = [];
    },
    assignDataTask(index) {
      let tarea = this.$data.tasksProcessModal[index];

      this.$data.taskModal.id = tarea.id;
      this.$data.taskModal.name = tarea.name;
      this.$data.taskModal.order = tarea.order;
      this.$data.taskModal.index = index;
    },
    clearDataTask() {
      this.$data.taskModal.id = "";
      this.$data.taskModal.name = "";
      this.$data.taskModal.order = 0;
      this.$data.taskModal.index = -1;
    },
    confirmarTarea() {
      if (this.$data.taskModal.name != "") {
        let data = {
          id: this.$data.taskModal.id,
          name: this.$data.taskModal.name,
          type: this.$data.taskModal.type,
          order: this.$data.taskModal.order,
          index: this.$data.taskModal.index,
        };

        let index = this.$data.taskModal.index;

        if (index > -1) {
          Vue.set(this.$data.tasksProcessModal, index, data);
          this.$data.newTasks.push(data);
        } else {
          let order = this.$data.tasksProcessModal.length + 1;
          data.order = order;
          data.index = order - 1;

          this.$data.tasksProcessModal.push(data);
          this.$data.newTasks.push(data);
        }
      }
    },
    eliminarTarea(index) {
      this.$data.deleteTasks.push(this.$data.tasksProcessModal[index]);
      this.$data.tasksProcessModal.splice(index, 1);
    },
    clearDataDocument() {
      this.$data.documentModal.id = "";
      this.$data.documentModal.file = "";
      this.$data.documentModal.type = "";
      this.$data.documentModal.index = -1;
    },
    previewFiles(event) {
      let files = event.target.files;

      if (files.length > 0) {
        this.$data.documentModal.name = files[0].name;

        let reader = new FileReader();

        reader.onload = (e) => {
          this.$data.documentModal.file = e.target.result;
        };

        reader.readAsDataURL(files[0]);
      }
    },
    confirmarDocumento() {
      let data = {
        id: this.$data.documentModal.id,
        name: this.$data.documentModal.name,
        file: this.$data.documentModal.file,
        type: this.$data.documentModal.type,
      };

      let index = this.$data.documentModal.index;

      if (index > -1) {
        Vue.set(this.$data.documentationsProcessModal, index, data);
        this.$data.newDocuments.push(data);
      } else {
        this.$data.documentationsProcessModal.push(data);
        this.$data.newDocuments.push(data);
      }

      if (data.type == 3) {
        Swal.fire({
          title: "¿Quiere importar las tareas del archivo BPMN?",
          text: "Este archivo debe ser de extension BPMN",
          icon: "warning",
          showCancelButton: true,
          confirmButtonText: "Sí",
          cancelButtonText: "No",
        }).then((result) => {
          if (result.value) {
            let xml = decodeURIComponent(
              atob(data.file.split(",")[1])
                .split("")
                .map(function (c) {
                  return "%" + ("00" + c.charCodeAt(0).toString(16)).slice(-2);
                })
                .join("")
            );

            let xmlDoc;

            if (window.DOMParser) {
              let parser = new DOMParser();
              xmlDoc = parser.parseFromString(xml, "text/xml");
            } else {
              xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
              xmlDoc.async = false;
              xmlDoc.loadXML(xml);
            }

            let processes = xmlDoc.getElementsByTagName("model:process");

            for (let index = 0; index < processes.length; index++) {
              let hijos = processes[index].childNodes;

              hijos.forEach((hijo) => {
                if (
                  hijo.tagName == "model:serviceTask" ||
                  hijo.tagName == "model:callActivity" ||
                  hijo.tagName == "model:receiveTask" ||
                  hijo.tagName == "model:sendTask" ||
                  hijo.tagName == "model:userTask" ||
                  hijo.tagName == "model:scriptTask" ||
                  hijo.tagName == "model:task"
                ) {
                  let nombre = hijo.getAttribute("name");
                  let type = 0;

                  if (hijo.tagName == "model:serviceTask") {
                    type = 1;
                  } else if (hijo.tagName == "model:callActivity") {
                    type = 2;
                  } else if (hijo.tagName == "model:receiveTask") {
                    type = 3;
                  } else if (hijo.tagName == "model:sendTask") {
                    type = 4;
                  } else if (hijo.tagName == "model:userTask") {
                    type = 5;
                  } else if (hijo.tagName == "model:scriptTask") {
                    type = 6;
                  } else if (hijo.tagName == "model:task") {
                    type = 7;
                  }

                  let order = this.$data.tasksProcessModal.length + 1;

                  if (nombre != "") {
                    let data = {
                      id: "",
                      name: nombre,
                      type: type,
                      order: order,
                      index: order - 1,
                    };

                    this.$data.tasksProcessModal.push(data);
                    this.$data.newTasks.push(data);
                  }
                }
              });
            }

            Swal.fire(
              "Añadidas",
              "Se han añadido las tareas procesadas del archivo",
              "success"
            );
          }
        });
      }
    },

    eliminarDocumento(index) {
      this.$data.deleteDocuments.push(
        this.$data.documentationsProcessModal[index]
      );
      this.$data.documentationsProcessModal.splice(index, 1);
    },

    descargarDocumento(index) {
      let documento = this.$data.documentationsProcessModal[index];

      let link = document.createElement("a");
      link.href = window.URL.createObjectURL(dataURItoBlob(documento.file));
      link.download = documento.name;

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    clearDataParticipante() {
      this.$data.participanteModal.idUsuario = "";
      this.$data.participanteModal.role = "";
      this.$data.participanteModal.index = -1;
    },

    confirmarParticipante() {
      let usuario = {};

      this.$data.users.forEach((element) => {
        if (this.$data.participanteModal.id == element.id) {
          usuario = element;
        }
      });

      let data = {
        id: this.$data.participanteModal.id,
        email: usuario.email,
        name: usuario.name,
        surname: usuario.surname,
        role: this.$data.participanteModal.role,
      };

      this.$data.participantesProcessModal.push(data);
      this.$data.newParticipantes.push(data);
    },

    assignDataObjectives(index) {
      let objetivo = this.$data.objectivesProcessModal[index];

      this.$data.objectiveModal.id = objetivo.id;
      this.$data.objectiveModal.type = objetivo.type;
      this.$data.objectiveModal.name = objetivo.name;
      this.$data.objectiveModal.valor = objetivo.valor;
      this.$data.objectiveModal.index = index;
    },
    clearDataObjectives() {
      this.$data.objectiveModal.id = "";
      this.$data.objectiveModal.type = "";
      this.$data.objectiveModal.name = "";
      this.$data.objectiveModal.valor = "";
      this.$data.objectiveModal.index = -1;
    },

    confirmarObjetivo() {
      let data = {
        id: this.$data.objectiveModal.id,
        name: this.$data.objectiveModal.name,
        valor: this.$data.objectiveModal.valor,
        type: this.$data.objectiveModal.type,
      };

      let index = this.$data.objectiveModal.index;

      if (index > -1) {
        Vue.set(this.$data.objectivesProcessModal, index, data);
        this.$data.newObjectives.push(data);
      } else {
        this.$data.objectivesProcessModal.push(data);
        this.$data.newObjectives.push(data);
      }
    },

    eliminarObjetivo(index) {
      this.$data.deleteObjectives.push(
        this.$data.objectivesProcessModal[index]
      );
      this.$data.objectivesProcessModal.splice(index, 1);
    },

    guardarProceso() {
      if (this.$data.processModal != "") {
        let URL = "";

        let data = {
          name: this.$data.processModal.name,
          description: this.$data.processModal.description,
        };

        if (this.$data.processModal.index > -1) {
          let idProceso = this.$data.processModal.id;
          URL = "/processes/" + idProceso;

          axios
            .put(URL, data)
            .then((response) => {
              let proceso = {
                id: idProceso,
                name: data.name,
                description: data.description,
                type: "",
              };

              Vue.set(
                this.$data.processes,
                this.$data.processModal.index,
                proceso
              );

              this.$data.deleteTasks.forEach((element) => {
                let URL = "/tasks/" + element.id;

                axios
                  .delete(URL)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.newTasks.forEach((element) => {
                let URL = "";

                let tarea = {
                  name: element.name,
                  type: element.type,
                  processId: idProceso,
                };

                if (element.id > 0) {
                  URL = "/tasks/" + element.id;

                  axios
                    .put(URL, tarea)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                } else {
                  URL = "/tasks";

                  axios
                    .post(URL, tarea)
                    .then((response) => {
                      this.$data.tasksProcessModal[element.index].id = parseInt(
                        response.data
                      );
                    })
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
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

              this.$data.newDocuments.forEach((element) => {
                let URL = "/documentations";

                let documento = {
                  name: element.name,
                  type: element.type,
                  file: element.file,
                  processId: idProceso,
                };

                axios
                  .post(URL, documento)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.deleteParticipantes.forEach((element) => {
                let URL =
                  "/users/" + element.id + "/process/" + idProceso;

                axios
                  .delete(URL)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.newParticipantes.forEach((element) => {
                let URL = "/users/process";

                let participante = {
                  userId: element.id,
                  processId: idProceso,
                  role: element.role,
                };

                axios
                  .post(URL, participante)
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

              this.$data.newObjectives.forEach((element) => {
                let URL = "";

                let objetivo = {
                  name: element.name,
                  type: element.type,
                  valor: element.valor,
                  processId: idProceso,
                };

                if (element.id > 0) {
                  URL = "/objectives/" + element.id;

                  axios
                    .put(URL, objetivo)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                } else {
                  URL = "/objectives";

                  axios
                    .post(URL, objetivo)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
              });

              axios.get("/users/process/" + idProceso).then((response) => {
                let responsables = response.data;

                responsables.forEach((element) => {
                  let notification = {
                    message: "Se ha editado el proceso " + proceso.name,
                    typeElement: 2,
                    idElement: idProceso,
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
          URL = "/processes/";

          axios
            .post(URL, data)
            .then((response) => {
              let idProceso = parseInt(response.data);

              let proceso = {
                id: idProceso,
                name: data.name,
                description: data.description,
                type: "",
              };

              this.$data.processes.push(proceso);

              this.$data.deleteTasks.forEach((element) => {
                let URL = "/tasks/" + element.id;

                axios
                  .delete(URL)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.newTasks.forEach((element) => {
                let URL = "";

                let tarea = {
                  name: element.name,
                  type: element.type,
                  processId: idProceso,
                };

                if (element.id > 0) {
                  URL = "/tasks/" + element.id;

                  axios
                    .put(URL, tarea)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                } else {
                  URL = "/tasks";

                  axios
                    .post(URL, tarea)
                    .then((response) => {
                      this.$data.tasksProcessModal[element.index].id = parseInt(
                        response.data
                      );
                    })
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
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

              this.$data.newDocuments.forEach((element) => {
                let URL = "/documentations";

                let documento = {
                  name: element.name,
                  type: element.type,
                  file: element.file,
                  processId: idProceso,
                };

                axios
                  .post(URL, documento)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.deleteParticipantes.forEach((element) => {
                let URL =
                  "/users/" + element.id + "/process/" + idProceso;

                axios
                  .delete(URL)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.newParticipantes.forEach((element) => {
                let URL = "/users/process";

                let participante = {
                  userId: element.id,
                  processId: idProceso,
                  role: element.role,
                };

                axios
                  .post(URL, participante)
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

              this.$data.newObjectives.forEach((element) => {
                let URL = "";

                let objetivo = {
                  name: element.name,
                  type: element.type,
                  valor: element.valor,
                  processId: idProceso,
                };

                if (element.id > 0) {
                  URL = "/objectives/" + element.id;

                  axios
                    .put(URL, objetivo)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                } else {
                  URL = "/objectives";

                  axios
                    .post(URL, objetivo)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
              });

              axios.get("/users/process/" + idProceso).then((response) => {
                let responsables = response.data;

                responsables.forEach((element) => {
                  let notification = {
                    message: "Se ha creado el proceso " + proceso.name,
                    typeElement: 2,
                    idElement: idProceso,
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
        }

        let _this = this;

        setTimeout(function () {
          _this.$data.tasksProcessModal.forEach((element) => {
            let data = {
              order: element.order,
            };

            axios
              .put("/tasks/" + element.id, data)
              .then((response) => {
                console.log(data);
              })
              .catch((error) => {
                console.log(error.response);
              });
          });
        }, 500);
      }
    },

    eliminarProceso(index) {
      Swal.fire({
        title: "¿Está seguro que quiere eliminar el proceso?",
        text: "Se perderá toda la información asociada y no se podrá recuperar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.value) {
          let proceso = this.$data.processes[index];

          axios.get("/users/process/" + idProceso).then((response) => {
            let responsables = response.data;

            responsables.forEach((element) => {
              let notification = {
                message: "Se ha creado el proceso " + proceso.name,
                typeElement: 2,
                idElement: idProceso,
                user_id: element.id,
              };

              axios
                .post("/notifications", notification)
                .then((response) => {})
                .catch((error) => {
                  console.log(error.response);
                });
            });

            axios
              .delete("/processes/" + proceso.id)
              .then((response) => {
                this.$data.processes.splice(index, 1);

                Swal.fire(
                  "Eliminado",
                  "El proceso y toda su información asociada ha sido eliminada",
                  "success"
                );
              })
              .catch((error) => {
                console.log(error.response);
              });
          });
        }
      });
    },

    verProceso(id) {
      window.location.href = "/processes/" + id;
    },

    verValueAdded(id) {
      window.location.href = "/processes/" + id + "/valueAdded";
    },
  },

  mounted() {
    this.user = this.usuario;
    this.permits = this.permisos;

    let URL = "/processes/all";

    axios.get(URL).then((response) => {
      let procesos = response.data;

      if (this.user.role == 1) {
        this.permits.forEach((permiso) => {
          procesos.forEach((proceso) => {
            if (permiso.id == proceso.id) {
              this.$data.processes.push(proceso);
            }
          });
        });
      } else if (this.user.role == 2) {
        this.$data.processes = procesos;
      }
    });

    axios.get("/users").then((response) => {
      this.$data.users = response.data;
    });
  },
};
</script>

<style>
</style>
