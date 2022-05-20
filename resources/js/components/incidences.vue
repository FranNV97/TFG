<template>
  <div class="container-fluid row justify-content-center">
    <div class="modal fade" id="modalCreateIssueProceso" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">Nueva incidencia</h4>
          </div>
          <div class="modal-body col-md-12">
            <label class="textoTable">
              <b>Seleccione un proceso</b>
            </label>
            <div class="row">
              <div
                class="col-md-4 pocoMargenBajo"
                v-for="(process, index) in processes"
                :key="process.id"
              >
                <div
                  class="card"
                  @click="confirmarProceso(index)"
                  data-dismiss="modal"
                  data-toggle="modal"
                  data-target="#modalCreateIssueGeneral"
                >
                  <div class="card-header titulo">{{ process.name }}</div>
                  <div class="card-body">
                    <p class="textoTable" style="white-space: pre-line;">{{ process.description }}</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="modal-footer">
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
            <h4 class="modal-title" v-if="incidenceModal.id == ''">Nueva incidencia</h4>
            <h4 class="modal-title" v-else>{{ incidenceModal.name }}</h4>

            <div class="float-right" v-if="incidenceModal.state == 1 && incidenceModal.id != ''">
              <button
                class="btn btn-outline-primary textoTable"
                @click="enviarNotificacionIncidencia(incidenceModal.index)"
              >Solicitar paso a incidencia</button>
            </div>
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
                v-if="incidenciaEditable"
              />
              <p class="textoTable" v-else>{{ incidenceModal.name }}</p>
            </div>
            <div
              class="form-group"
              v-if="incidenciaEditable || (incidenceModal.description != null && incidenceModal.description != '')"
            >
              <label class="textoTable">
                <b>Descripción</b>
              </label>
              <textarea
                class="form-control textoTable"
                placeholder="Descripción"
                v-model="incidenceModal.description"
                rows="4"
                v-if="incidenciaEditable"
              ></textarea>
              <p class="textoTable" v-else style="white-space: pre-line;">{{ incidenceModal.description }}</p>
            </div>

            <label class="textoTable" v-if="incidenciaEditable || incidenceModal.selected.length > 0">
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
              v-if="incidenciaEditable"
              class="pocoMargenBajo textoTable"
            ></multiselect>
            <ul v-else-if="incidenceModal.selected.length > 0" class="list-group pocoMargenBajo">
              <li class="list-group-item" v-for="task in incidenceModal.selected" :key="task.id"><p class="textoTable">{{ task.name }}</p></li>
            </ul>

            <p class="textoTable"
              v-if="incidenciaEditable || ((incidenciaEditable && imageRecortar != null) || incidenceModal.recorte != null)"
            >
              <b>Imagen de la parte afectada</b>
            </p>
            <button
              class="btn btn-secondary pocoMargenBajo textoTable"
              @click="abrirEditor()"
              data-toggle="modal"
              data-target="#modalRecortar"
              v-if="incidenciaEditable && imageRecortar != null"
            >Nuevo recorte</button>
            <img
              class="unaLinea pocoMargenBajo"
              :src="incidenceModal.recorte"
              v-if="incidenceModal.recorte != null && incidenceModal.recorte != ''"
            />
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-warning textoTable"
              data-dismiss="modal"
              data-toggle="modal"
              data-target="#modalCreateIssueProceso"
              v-if="incidenceModal.id == ''"
            >Volver</button>
            <button
              type="button"
              class="btn btn-primary textoTable"
              data-dismiss="modal"
              @click="confirmarIncidencia()"
              v-if="incidenciaEditable"
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
        <h1 class="col-md-3">Incidencias</h1>

        <div class="col-md-9">
          <div class="row justify-content-end">
            <div class="form-group pocoMargenDerecha">
              <label class="textoTable">
                <b>Busqueda rápida</b>
              </label>
              <input
                type="text"
                class="form-control textoTable"
                placeholder="Búsqueda rápida"
                v-model="search"
                @change="filtrar()"
              />
            </div>

            <div class="form-group pocoMargenDerecha">
              <label class="textoTable">
                <b>Estado</b>
              </label>
              <select class="form-control textoTable" v-model="status" @change="filtrar()">
                <option value="0">Todos</option>
                <option value="-1">Rechazada</option>
                <option value="1">Nueva</option>
                <option value="2">Pendiente</option>
              </select>
            </div>

            <button
              class="btn btn-primary btn-lg altoBoton"
              data-toggle="modal"
              data-target="#modalCreateIssueProceso"
              @click="clearDataIncidence()"
              v-if="processes.length > 0"
            >Crear incidencia</button>
          </div>
        </div>
      </div>
      <div class="row">
        <table class="table table-light table-striped">
          <thead class="thead-dark">
            <tr>
              <th class="textoCabeceraTable" style="width: 10%">Acciones</th>
              <th class="textoCabeceraTable" style="width: 20%">Nombre</th>
              <th class="textoCabeceraTable" style="width: 60%">Descripción</th>
              <th class="textoCabeceraTable" style="width: 10%">Estado</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(issue, index) in filtered" :key="issue.id">
              <td>
                <div class="row">
                  <button
                    class="btn sinBorde"
                    data-toggle="modal"
                    data-target="#modalCreateIssueGeneral"
                    @click="assignDataIncidence(index, false)"
                  >
                    <i class="fa fa-search fa-lg"></i>
                  </button>
                  <button
                    class="btn sinBorde"
                    v-if="issue.state != -1 && issue.state != 5"
                    data-toggle="modal"
                    data-target="#modalCreateIssueGeneral"
                    @click="assignDataIncidence(index, true)"
                  >
                    <i class="fa fa-pencil fa-lg"></i>
                  </button>

                  <button
                    v-if="issue.state != -1"
                    class="btn sinBorde"
                    @click="eliminarIncidencia(index)"
                  >
                    <i class="fa fa-trash fa-lg"></i>
                  </button>
                </div>
              </td>
              <td><p class="textoTable">{{ issue.name }}</p></td>
              <td><p class="textoTable">{{ issue.description }}</p></td>
              <td>
                <p class="textoTable" v-if="issue.state == -1">Rechazada</p>
                <p class="textoTable" v-else-if="issue.state == 1">Nueva</p>
                <p class="textoTable" v-else-if="issue.state == 2">Pendiente</p>
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

export default {
  props: ["usuario", "permisos"],

  components: {
    Multiselect,
  },

  data() {
    return {
      user: {},
      issues: [],
      filtered: [],
      status: 0,
      search: "",
      processes: [],
      incidenceModal: {
        id: "",
        name: "",
        description: "",
        process: {},
        index: -1,
        selected: "",
        state: 1,
        type: 1,
        recorte: "",
      },
      tasksProcess: [],
      incidenciaEditable: false,
      imageRecortar: "",
      image64: "",
      permits: [],
    };
  },

  methods: {
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
    filtrar() {
      this.$data.filtered = this.$data.issues.filter((issue) => {
        if (this.$data.status != 0 && issue.state != this.$data.status) {
          return false;
        }

        if (
          this.$data.search != "" &&
          !(
            issue.name.includes(this.$data.search) ||
            issue.description.includes(this.$data.search)
          )
        ) {
          return false;
        }

        return true;
      });
    },

    clearDataIncidence() {
      this.$data.incidenceModal.id = "";
      this.$data.incidenceModal.name = "";
      this.$data.incidenceModal.description = "";
      this.$data.incidenceModal.process = {};
      this.$data.incidenceModal.index = -1;
      this.$data.incidenceModal.selected = "";
      this.$data.incidenceModal.state = 1;
      this.$data.incidenceModal.type = 1;
      this.$data.incidenceModal.recorte = "";

      this.$data.incidenciaEditable = true;
    },

    assignDataIncidence(index, editable) {
      let issue = this.$data.issues[index];

      this.$data.incidenceModal.id = issue.id;
      this.$data.incidenceModal.name = issue.name;
      this.$data.incidenceModal.description = issue.description;
      this.$data.incidenceModal.index = index;
      this.$data.incidenceModal.state = issue.state;
      this.$data.incidenceModal.type = issue.type;
      this.$data.incidenceModal.recorte = issue.recorte;

      axios.get("/processes/bean/" + issue.process_id).then((response) => {
        this.$data.incidenceModal.process = response.data;

        axios
          .get(
            "/documentations/process/" +
              this.$data.incidenceModal.process.id +
              "/cut"
          )
          .then((response) => {
            this.$data.imageRecortar = response.data.file;
          });
      });

      axios.get("/tasks/process/" + issue.process_id).then((response) => {
        this.$data.tasksProcess = response.data;
      });

      axios.get("/issues/" + issue.id + "/tasks").then((response) => {
        this.$data.incidenceModal.selected = response.data;
      });

      this.$data.incidenciaEditable = editable;
    },

    confirmarProceso(index) {
      let proceso = this.$data.processes[index];

      this.$data.incidenceModal.process = proceso;

      axios
        .get(
          "/documentations/process/" +
            this.$data.incidenceModal.process.id +
            "/cut"
        )
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
        modificado: this.$data.user.id,
      };

      if (this.$data.incidenceModal.index > -1) {
        axios
          .put("/issues/" + data.id, data)
          .then((response) => {
            data.process_id = data.processId;
            Vue.set(this.$data.issues, this.$data.incidenceModal.index, data);

            axios
              .delete("/issues/" + data.id + "/tasks")
              .then((response) => {
                let tareas = this.$data.incidenceModal.selected;

                tareas.forEach((element) => {
                  let issueTask = {
                    issueId: data.id,
                    taskId: element.id,
                  };

                  axios
                    .post("/issues/tasks", issueTask)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                });
              })
              .catch((error) => {
                console.log(error);
              });

            axios.get("/users/process/" + data.processId).then((response) => {
              let responsables = response.data;

              responsables.forEach((element) => {
                let notification = {
                  message: "Se ha modificado la incidencia " + data.name,
                  typeElement: 4,
                  idElement: data.id,
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
        axios
          .post("/issues", data)
          .then((response) => {
            let idIssue = parseInt(response.data);

            let issue = {
              id: idIssue,
              name: data.name,
              description: data.description,
              process_id: data.processId,
              type: data.type,
              state: data.state,
              recorte: data.recorte,
            };

            this.$data.issues.push(issue);

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

            axios.get("/users/process/" + issue.process_id).then((response) => {
              let responsables = response.data;

              responsables.forEach((element) => {
                let notification = {
                  message: "Se ha creado la incidencia " + issue.name,
                  typeElement: 4,
                  idElement: issue.id,
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
    },

    enviarNotificacionIncidencia(index) {
      let incidencia = this.$data.issues[index];

      let data = {
        state: 2,
        modificado: this.$data.user.id,
      };

      axios
        .put("/issues/" + incidencia.id, data)
        .then((response) => {
          this.$data.incidenceModal.state = 2;
          incidencia.state = 2;

          Vue.set(this.$data.issues, index, incidencia);

          Swal.fire(
            "Enviado",
            "La solicitud para pasar a ser un problema ya se ha enviado",
            "success"
          );

          axios
            .get("/users/process/" + incidencia.process_id + "/role/2")
            .then((response) => {
              let responsables = response.data;

              responsables.forEach((element) => {
                let notification = {
                  message:
                    "Se ha solicitado que la incidencia " +
                    incidencia.name +
                    " pase a ser un problema",
                  typeElement: 1,
                  idElement: incidencia.id,
                  user_id: element.id,
                };

                axios
                  .post("/notifications", notification)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            })
            .catch((error) => {
              console.log(error.response);
            });
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    eliminarIncidencia(index) {
      Swal.fire({
        title: "¿Está seguro que quiere eliminar la incidencia?",
        text: "Se perderá toda la información asociada y no se podrá recuperar",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí",
        cancelButtonText: "No",
      }).then((result) => {
        if (result.value) {
          let issue = this.$data.issues[index];

          axios.get("/users/process/" + issue.process_id).then((response) => {
            let responsables = response.data;

            responsables.forEach((element) => {
              let notification = {
                message: "Se ha eliminado la incidencia " + issue.name,
                typeElement: 4,
                idElement: issue.id,
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

          axios
            .delete("/issues/" + issue.id)
            .then((response) => {
              this.$data.issues.splice(index, 1);

              Swal.fire(
                "Eliminado",
                "La incidencia y toda su información asociada ha sido eliminada",
                "success"
              );
            })
            .catch((error) => {
              console.log(error.response);
            });
        }
      });
    },
  },

  mounted() {
    this.user = this.usuario;
    this.permits = this.permisos;

    let URL = "/issues/type/1";

    axios.get(URL).then((response) => {
      let incidencias = response.data;

      if (this.user.role == 1) {
        this.permits.forEach((permiso) => {
          incidencias.forEach((incidencia) => {
            if (incidencia.creado == this.user.id || (permiso.id == incidencia.process_id && permiso.role > 1)) {
              this.$data.issues.push(incidencia);
              this.$data.filtered.push(incidencia);
            }
          });
        });
      } else if (this.user.role == 2) {
        this.$data.issues = incidencias;
        this.$data.filtered = incidencias;
      }
    });

    axios.get("/processes/all").then((response) => {
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
  },
};
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
