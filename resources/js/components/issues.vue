<template>
  <div class="container-fluid row justify-content-center">
    <div class="modal fade" id="modalCreateIssueGeneral" role="dialog">
      <div class="modal-dialog modal-xl">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <nav aria-label="breadcrumb">
              <ol class="breadcrumb pocoMargenDerecha">
                <li
                  v-for="element in breadcrumb"
                  :key="element.id"
                  style="font-size: 20px;"
                  class="breadcrumb-item"
                  aria-current="page"
                  @click="assignDataIncidenceBean(element, incidenciaEditable)"
                >
                  <a href="javascript:void(0);">{{ element.name }}</a>
                </li>
                <li
                  style="font-size: 20px;"
                  class="breadcrumb-item active"
                  aria-current="page"
                >{{ incidenceModal.name }}</li>
              </ol>
            </nav>

            <div class="float-right">
              <button
                class="btn btn-outline-primary textoTable"
                v-if="tienePermiso('editar', incidenceModal) == true"
                @click="fiveWhys(incidenceModal.id)"
              >5 Whys</button>
              <button
                class="btn btn-outline-secondary textoTable"
                v-if="!issueRegisterExistente && incidenciaEditable"
                @click="nuevoRegistro()"
              >Nuevo registro</button>
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
              <p class="textoTable">{{ incidenceModal.name }}</p>
            </div>
            <div
              class="form-group"
              v-if="incidenceModal.description != null && incidenceModal.description != ''"
            >
              <label class="textoTable">
                <b>Descripción</b>
              </label>
              <p class="textoTable" style="white-space: pre-line;">{{ incidenceModal.description }}</p>
            </div>

            <label class="textoTable" v-if="incidenceModal.length > 0">
              <b>Tareas afectadas</b>
            </label>
            <ul class="list-group pocoMargenBajo" v-if="incidenceModal.length > 0">
              <li class="list-group-item" v-for="task in incidenceModal.selected" :key="task.id">
                <p class="textoTable">{{ task.name }}</p>
              </li>
            </ul>

            <p
              class="textoTable"
              v-if="incidenceModal.recorte != null && incidenceModal.recorte != ''"
            >
              <b>Imagen de la parte afectada</b>
            </p>

            <img
              class="unaLinea pocoMargenBajo"
              :src="incidenceModal.recorte"
              v-if="incidenceModal.recorte != null && incidenceModal.recorte != ''"
            />

            <label class="textoTable">
              <b>Estado</b>
            </label>

            <select
              class="form-control pocoMargenBajo textoTable"
              v-model="incidenceModal.state"
              v-if="incidenciaEditable"
            >
              <option value="3">Aceptada</option>
              <option value="4">En proceso</option>
              <option value="5">Resuelta</option>
            </select>

            <p class="textoTable" v-if="incidenceModal.state == 3 && !incidenciaEditable">Aceptada</p>
            <p class="textoTable" v-if="incidenceModal.state == 4 && !incidenciaEditable">En proceso</p>
            <p class="textoTable" v-if="incidenceModal.state == 5 && !incidenciaEditable">Resuelta</p>

            <div v-if="incidenceModal.subproblems.length > 0">
              <label class="textoTable">
                <b>Subproblemas</b>
              </label>

              <table class="table table-striped">
                <thead class="thead-dark">
                  <tr>
                    <th class="textoCabeceraTable" style="width: 20%">Acciones</th>
                    <th class="textoCabeceraTable" style="width: 35%">Nombre</th>
                    <th class="textoCabeceraTable" style="width: 45%">Descripción</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="sp in incidenceModal.subproblems" :key="sp.id">
                    <td>
                      <button class="btn sinBorde" @click="assignDataIncidenceBean(sp, false)">
                        <i class="fa fa-search fa-lg"></i>
                      </button>
                      <button
                        class="btn sinBorde"
                        v-if="incidenciaEditable"
                        @click="assignDataIncidenceBean(sp, true)"
                      >
                        <i class="fa fa-pencil fa-lg"></i>
                      </button>
                    </td>
                    <td>
                      <p class="textoTable">{{ sp.name }}</p>
                    </td>
                    <td>
                      <p class="textoTable">{{ sp.description }}</p>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <h4 v-if="issueRegisterExistente" class="pocoMargenBajo">Registro de problema</h4>

            <div class="form-group" v-if="issueRegisterExistente">
              <label class="textoTable">
                <b>Prioridad</b>
              </label>
              <input
                type="number"
                class="form-control textoTable"
                placeholder="Prioridad"
                v-model.number="incidenceModal.issueRegister.priority"
                v-if="incidenciaEditable"
              />
              <p class="textoTable" v-else>{{ incidenceModal.issueRegister.priority }}</p>
            </div>

            <div class="form-group" v-if="issueRegisterExistente">
              <label class="textoTable">
                <b>Impacto cuantitativo</b>
              </label>
              <textarea
                class="form-control textoTable"
                placeholder="Impacto cuantitativo"
                v-model="incidenceModal.issueRegister.quantitativeImpact"
                rows="4"
                v-if="incidenciaEditable"
              ></textarea>
              <p
                class="textoTable"
                v-else
                style="white-space: pre-line;"
              >{{ incidenceModal.issueRegister.quantitativeImpact }}</p>
            </div>

            <div class="form-group" v-if="issueRegisterExistente">
              <label class="textoTable">
                <b>Impacto cualitativo</b>
              </label>
              <textarea
                class="form-control textoTable"
                placeholder="Impacto cualitativo"
                v-model="incidenceModal.issueRegister.qualitativeImpact"
                rows="4"
                v-if="incidenciaEditable"
              ></textarea>
              <p
                class="textoTable"
                v-else
                style="white-space: pre-line;"
              >{{ incidenceModal.issueRegister.qualitativeImpact }}</p>
            </div>

            <div class="pocoMargenBajo" v-if="issueRegisterExistente">
              <label class="textoTable">
                <b>Asunciones</b>
              </label>
              <button
                style="float: right;"
                class="btn btn-outline-secondary textoTable"
                v-on:click="clearDataDocument()"
                data-toggle="modal"
                data-target="#modalCreateDocument"
                v-if="incidenciaEditable"
              >Añadir asunción</button>
            </div>

            <table class="table table-striped" v-if="issueRegisterExistente">
              <thead class="thead-dark">
                <tr>
                  <th class="textoCabeceraTable" style="width: 20%">Acciones</th>
                  <th class="textoCabeceraTable" style="width: 80%">Nombre</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="(doc, index) in incidenceModal.issueRegister.asumptions" :key="doc.id">
                  <td>
                    <button v-on:click="descargarDocumento(index)" class="btn sinBorde">
                      <i class="fa fa-download fa-lg"></i>
                    </button>
                    <button
                      class="btn sinBorde"
                      v-if="incidenciaEditable"
                      v-on:click="eliminarDocumento(index)"
                    >
                      <i class="fa fa-trash fa-lg"></i>
                    </button>
                  </td>
                  <td>
                    <p class="textoTable">{{ doc.name }}</p>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
          <div class="modal-footer">
            <button
              type="button"
              class="btn btn-primary textoTable"
              data-dismiss="modal"
              @click="confirmarProblema()"
              v-if="incidenciaEditable"
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
            <h4 class="modal-title">Nueva asunción</h4>
          </div>
          <div class="modal-body col-md-12">
            <div class="form-group">
              <label class="textoTable">
                <b>Documento</b>
              </label>
              <input class="form-control-file" type="file" @change="previewFiles" />
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

    <div class="col-md-10">
      <div class="row justify-content-between">
        <h1 class="col-md-3">Problemas</h1>
      </div>
      <div class="row">
        <vue-ads-table-tree
          class="col-md-12"
          :columns="columns"
          :rows="filtered"
          :call-children="callChildren"
        >
          <template slot="bottom">
            <span></span>
          </template>
          <template slot="no-rows">No existen resultados</template>
          <template v-for="issue in problemasTotales" :slot="'acciones_' + issue._id">
            <div class="row" :key="issue.id">
              <button
                class="btn sinBorde"
                data-toggle="modal"
                data-target="#modalCreateIssueGeneral"
                @click="assignDataIncidenceBean(issue, false)"
              >
                <i class="fa fa-search fa-lg"></i>
              </button>
              <button
                class="btn sinBorde"
                v-if="issue.state != -1 && issue.state != 5 && (user.role == 2 || tienePermiso('editar', issue) == true)"
                data-toggle="modal"
                data-target="#modalCreateIssueGeneral"
                @click="assignDataIncidenceBean(issue, true)"
              >
                <i class="fa fa-pencil fa-lg"></i>
              </button>
            </div>
          </template>
        </vue-ads-table-tree>
      </div>
    </div>
  </div>
</template>

<script>
import "../../../node_modules/vue-ads-table-tree/dist/vue-ads-table-tree.css";
import VueAdsTableTree from "vue-ads-table-tree";

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
    VueAdsTableTree,
  },

  data() {

    return {
      user: {},
      issues: [],
      filtered: [],
      status: 0,
      search: "",
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
        subproblems: [],
        process_id: 0,
        issueRegister: {
          issue_id: 0,
          priority: 0,
          quantitativeImpact: "",
          qualitativeImpact: "",
          nuevo: false,
          asumptions: [],
        },
      },
      tasksProcess: [],
      incidenciaEditable: false,
      issueRegisterExistente: false,
      documentModal: {
        id: "",
        file: "",
        type: 1,
        index: -1,
      },
      newDocuments: [],
      deleteDocuments: [],
      breadcrumb: [],
      columns: [
        {
          property: "acciones",
          title: "Acciones",
        },
        {
          property: "name",
          title: "Nombre",
          collapseIcon: true,
          filterable: true,
        },
        {
          property: "description",
          title: "Descripción",
          filterable: true,
        },
        {
          property: "estado",
          title: "Estado",
          filterable: true,
        },
      ],
      problemasTotales: [],
      permits: [],
    };
  },

  methods: {
    tienePermiso(accion, issue) {
      switch (accion) {
        case "editar":
          return (
            this.$data.permits.filter(
              (p) => p.id == issue.process_id && p.role > 1
            ).length > 0
          );

          break;

        default:
          break;
      }
    },
    assignDataIncidence(index, editable) {
      let issue = this.$data.issues[index];

      this.$data.breadcrumb = [];

      let _this = this;
      _this.obtenerProblemasPadre(issue);

      this.$data.incidenceModal.id = issue.id;
      this.$data.incidenceModal.name = issue.name;
      this.$data.incidenceModal.description = issue.description;
      this.$data.incidenceModal.index = index;
      this.$data.incidenceModal.state = issue.state;
      this.$data.incidenceModal.type = issue.type;
      this.$data.incidenceModal.recorte = issue.recorte;
      this.$data.incidenceModal.process_id = issue.process_id;

      axios.get("/processes/bean/" + issue.process_id).then((response) => {
        this.$data.incidenceModal.process = response.data;
      });

      axios.get("/tasks/process/" + issue.process_id).then((response) => {
        this.$data.tasksProcess = response.data;
      });

      axios.get("/issues/" + issue.id + "/tasks").then((response) => {
        this.$data.incidenceModal.selected = response.data;
      });

      this.$data.incidenciaEditable = editable;

      axios.get("/issues/" + issue.id + "/subproblems").then((response) => {
        this.$data.incidenceModal.subproblems = response.data;
      });

      axios
        .get("/issues/" + issue.id + "/issueRegister")
        .then((response) => {
          this.$data.incidenceModal.issueRegister.nuevo = false;

          if (response.data == "") {
            this.$data.issueRegisterExistente = false;

            this.$data.incidenceModal.issueRegister.issue_id = 0;
            this.$data.incidenceModal.issueRegister.priority = 0;
            this.$data.incidenceModal.issueRegister.quantitativeImpact = "";
            this.$data.incidenceModal.issueRegister.qualitativeImpact = "";
            this.$data.incidenceModal.issueRegister.asumptions = [];
          } else {
            this.$data.issueRegisterExistente = true;

            let issueRegister = response.data;

            this.$data.incidenceModal.issueRegister.issue_id =
              issueRegister.issue_id;
            this.$data.incidenceModal.issueRegister.priority =
              issueRegister.priority;
            this.$data.incidenceModal.issueRegister.quantitativeImpact =
              issueRegister.quantitativeImpact;
            this.$data.incidenceModal.issueRegister.qualitativeImpact =
              issueRegister.qualitativeImpact;

            axios
              .get("/documentations/issueRegister/" + issueRegister.issue_id)
              .then((response) => {
                this.$data.incidenceModal.issueRegister.asumptions =
                  response.data;
              });
          }
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    assignDataIncidenceBean(issue, editable) {
      this.$data.breadcrumb = [];

      let _this = this;
      _this.obtenerProblemasPadre(issue);

      this.$data.incidenceModal.id = issue.id;
      this.$data.incidenceModal.name = issue.name;
      this.$data.incidenceModal.description = issue.description;
      this.$data.incidenceModal.index = -100;
      this.$data.incidenceModal.state = issue.state;
      this.$data.incidenceModal.type = issue.type;
      this.$data.incidenceModal.recorte = issue.recorte;
      this.$data.incidenceModal.process_id = issue.process_id;

      axios.get("/processes/bean/" + issue.process_id).then((response) => {
        this.$data.incidenceModal.process = response.data;
      });

      axios.get("/tasks/process/" + issue.process_id).then((response) => {
        this.$data.tasksProcess = response.data;
      });

      axios.get("/issues/" + issue.id + "/tasks").then((response) => {
        this.$data.incidenceModal.selected = response.data;
      });

      this.$data.incidenciaEditable = editable;

      axios.get("/issues/" + issue.id + "/subproblems").then((response) => {
        this.$data.incidenceModal.subproblems = response.data;
      });

      axios
        .get("/issues/" + issue.id + "/issueRegister")
        .then((response) => {
          this.$data.incidenceModal.issueRegister.nuevo = false;

          if (response.data == "") {
            this.$data.issueRegisterExistente = false;

            this.$data.incidenceModal.issueRegister.issue_id = 0;
            this.$data.incidenceModal.issueRegister.priority = 0;
            this.$data.incidenceModal.issueRegister.quantitativeImpact = "";
            this.$data.incidenceModal.issueRegister.qualitativeImpact = "";
            this.$data.incidenceModal.issueRegister.asumptions = [];
          } else {
            this.$data.issueRegisterExistente = true;

            let issueRegister = response.data;

            this.$data.incidenceModal.issueRegister.issue_id =
              issueRegister.issue_id;
            this.$data.incidenceModal.issueRegister.priority =
              issueRegister.priority;
            this.$data.incidenceModal.issueRegister.quantitativeImpact =
              issueRegister.quantitativeImpact;
            this.$data.incidenceModal.issueRegister.qualitativeImpact =
              issueRegister.qualitativeImpact;

            axios
              .get("/documentations/issueRegister/" + issueRegister.issue_id)
              .then((response) => {
                this.$data.incidenceModal.issueRegister.asumptions =
                  response.data;
              });
          }
        })
        .catch((error) => {
          console.log(error.response);
        });
    },

    confirmarProblema() {
      let estadoCambiado = {
        state: this.$data.incidenceModal.state,
        modificado: this.$data.user.id,
      };

      axios
        .put(
          "/issues/" + this.$data.incidenceModal.id,
          estadoCambiado
        )
        .then((response) => {
          if (this.$data.incidenceModal.index != -100) {
            let problema = this.$data.issues[this.$data.incidenceModal.index];
            problema.state = this.$data.incidenceModal.state;

            Vue.set(
              this.$data.issues,
              this.$data.incidenceModal.index,
              problema
            );
          }
        })
        .catch((error) => {
          console.log(error.response);
        });

      axios.get(
        "/users/process/" +
          this.$data.incidenceModal.process.id.then((response) => {
            let responsables = response.data;

            responsables.forEach((element) => {
              let notification = {
                message:
                  "Se ha modificado el problema " +
                  this.$data.incidenceModal.name,
                typeElement: 4,
                idElement: this.$data.incidenceModal.id,
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
      );

      if (this.$data.issueRegisterExistente) {
        let data = {
          issue_id: this.$data.incidenceModal.id,
          priority: this.$data.incidenceModal.issueRegister.priority,
          quantitativeImpact: this.$data.incidenceModal.issueRegister
            .quantitativeImpact,
          qualitativeImpact: this.$data.incidenceModal.issueRegister
            .qualitativeImpact,
        };

        if (this.$data.incidenceModal.issueRegister.nuevo) {
          axios
            .post("/issues/issueRegister", data)
            .then((response) => {
              this.$data.incidenceModal.issueRegister.asumptions.forEach(
                (element) => {
                  let documento = {
                    name: element.name,
                    type: element.type,
                    file: element.file,
                    issueRegisterId: data.issue_id,
                  };

                  axios
                    .post("/documentations", documento)
                    .then((response) => {})
                    .catch((error) => {
                      console.log(error.response);
                    });
                }
              );
            })
            .catch((error) => {
              console.log(error.response);
            });
        } else {
          axios
            .put("/issues/issueRegister/" + data.issue_id, data)
            .then((response) => {
              this.$data.deleteDocuments.forEach((element) => {
                axios
                  .delete("/documentations/" + element.id)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });

              this.$data.newDocuments.forEach((element) => {
                let documento = {
                  name: element.name,
                  type: element.type,
                  file: element.file,
                  issueRegisterId: data.issue_id,
                };

                axios
                  .post("/documentations", documento)
                  .then((response) => {})
                  .catch((error) => {
                    console.log(error.response);
                  });
              });
            })
            .catch((error) => {
              console.log(error.response);
            });
        }
      }
    },

    nuevoRegistro() {
      this.$data.issueRegisterExistente = true;
      this.$data.incidenceModal.issueRegister.nuevo = true;
    },

    clearDataDocument() {
      this.$data.documentModal.id = "";
      this.$data.documentModal.file = "";
      this.$data.documentModal.type = 1;
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
        Vue.set(
          this.$data.incidenceModal.issueRegister.asumptions,
          index,
          data
        );
        this.$data.newDocuments.push(data);
      } else {
        this.$data.incidenceModal.issueRegister.asumptions.push(data);
        this.$data.newDocuments.push(data);
      }
    },

    eliminarDocumento(index) {
      this.$data.deleteDocuments.push(
        this.$data.incidenceModal.issueRegister.asumptions[index]
      );
      this.$data.incidenceModal.issueRegister.asumptions.splice(index, 1);
    },

    descargarDocumento(index) {
      let documento = this.$data.incidenceModal.issueRegister.asumptions[index];

      let link = document.createElement("a");
      link.href = window.URL.createObjectURL(dataURItoBlob(documento.file));
      link.download = documento.name;

      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    },

    fiveWhys(id) {
      window.location.href = "/issues/" + id + "/fiveWhys";
    },

    obtenerProblemasPadre(issue) {
      let _this = this;

      if (issue.issue_id != null) {
        axios.get("/issues/" + issue.issue_id).then((response) => {
          let problema = response.data;

          this.$data.breadcrumb.push(problema);

          _this.obtenerProblemasPadre(problema);
        });
      } else {
        this.$data.breadcrumb = this.$data.breadcrumb.reverse();
      }
    },

    obtenerProblemasTotales(lista) {
      lista.forEach((element) => {
        this.$data.problemasTotales.push(element);

        if (element._children != undefined && element._children.length > 0) {
          let _this = this;
          _this.obtenerProblemasTotales(element._children);
        }
      });
    },

    sleep(ms) {
      return new Promise((resolve) => setTimeout(resolve, ms));
    },

    async callChildren(parent) {
      let hijos = [];

      let res = await axios.get("/issues/" + parent.id + "/subproblems");
      let data = res.data;

      for (let index = 0; index < data.length; index++) {
        let element = data[index];

        element._id = element.id;

        if (element.state == 3) {
          element.estado = "Aceptada";
        } else if (element.state == 4) {
          element.estado = "En proceso";
        } else if (element.state == 5) {
          element.estado = "Resuelta";
        }

        let response = await axios.get(
          "/issues/" + element.id + "/subproblems"
        );
        let data2 = response.data;

        if (data2.length > 0) {
          element._hasChildren = true;
        }
        hijos.push(element);

        if (index == data.length - 1) {
          this.$data.problemasTotales = [];
          this.obtenerProblemasTotales(this.$data.filtered);

          hijos.forEach((e) => {
            this.$data.problemasTotales.push(e);
          });

          return hijos;
        }
      }
    },

    ishikawa(id) {
      window.location.href = "/issues/" + id + "/ishikawa";
    },
  },

  mounted() {
    this.user = this.usuario;
    this.permits = this.permisos;

    axios
      .get("/issues/root/elements")
      .then((response) => {
        let problemas = response.data;

        if (this.user.role == 1) {
          this.permits.forEach((permiso) => {
            problemas.forEach((problema) => {
              if (
                problema.creado == this.user.id ||
                (permiso.id == problema.process_id && permiso.role > 1)
              ) {
                this.$data.issues.push(problema);
              }
            });
          });
        } else if (this.user.role == 2) {
          this.$data.issues = problemas;
        }

        for (let index = 0; index < this.$data.issues.length; index++) {
          let element = this.$data.issues[index];
          element._id = element.id;

          if (element.state == 3) {
            element.estado = "Aceptada";
          } else if (element.state == 4) {
            element.estado = "En proceso";
          } else if (element.state == 5) {
            element.estado = "Resuelta";
          }

          axios.get("/issues/" + element.id + "/subproblems").then((res) => {
            if (res.data.length > 0) {
              element._hasChildren = true;
            }

            if (index == this.$data.issues.length - 1) {
              this.$data.filtered = this.$data.issues;
              this.obtenerProblemasTotales(this.$data.filtered);
            }
          });
        }
      })
      .catch((error) => {
        console.log(error.response);
      });
  },
};
</script>

