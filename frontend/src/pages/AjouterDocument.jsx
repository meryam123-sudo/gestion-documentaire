import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function AjouterDocument() {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));

  const [categories, setCategories] = useState([]);
  const [dossiers, setDossiers] = useState([]);

  const [form, setForm] = useState({
    titre: "",
    reference: "",
    description: "",
    typeDocument: "PDF",
    niveauConfidentialite: "Confidentiel",
    idCategorie: "",
    idDossier: "",
    dateEmission: "",
    dateExpiration: "",
    idUtilisateur: utilisateur?.idUtilisateur,
    fichier: null,
  });

  const [message, setMessage] = useState("");

  useEffect(() => {
    api.get("/categories").then((res) => setCategories(res.data.data || res.data));
    api.get("/dossiers").then((res) => setDossiers(res.data.data || res.data));
  }, []);

  const handleChange = (e) => {
    const { name, value, files } = e.target;

    if (files) {
      setForm({
        ...form,
        [name]: files[0],
      });
      return;
    }

    setForm({
      ...form,
      [name]: value,
    });
  };

  const deposerDocument = async (e) => {
    e.preventDefault();
    setMessage("");

    const data = new FormData();

    Object.keys(form).forEach((key) => {
      data.append(key, form[key] ?? "");
    });

    data.append("dateEmission", form.dateEmission);
    data.append("dateExpiration", form.dateExpiration);

    try {
      await api.post("/documents", data, {
        headers: {
          "Content-Type": "multipart/form-data",
        },
      });

      setMessage("Document ajouté avec succès");
    } catch (error) {
      setMessage(error.response?.data?.message || "Erreur lors du dépôt");
    }
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Ajouter un document</h1>
        <p>Déposer un nouveau document avec ses métadonnées</p>
      </div>

      {message && <div className="info-message">{message}</div>}

      <form className="form-card" onSubmit={deposerDocument}>
        <input
          name="titre"
          placeholder="Titre"
          value={form.titre}
          onChange={handleChange}
        />

        <input
          name="reference"
          placeholder="Référence ex: DOC-005"
          value={form.reference}
          onChange={handleChange}
        />

        <textarea
          name="description"
          placeholder="Description"
          value={form.description}
          onChange={handleChange}
        ></textarea>

        <select
          name="typeDocument"
          onChange={handleChange}
          value={form.typeDocument}
        >
          <option value="PDF">PDF</option>
          <option value="DOC">DOC</option>
          <option value="DOCX">DOCX</option>
          <option value="IMAGE">Image</option>
        </select>

        <select
          name="niveauConfidentialite"
          onChange={handleChange}
          value={form.niveauConfidentialite}
        >
          <option value="Public">Public</option>
          <option value="Interne">Interne</option>
          <option value="Confidentiel">Confidentiel</option>
        </select>

        <select
          name="idCategorie"
          value={form.idCategorie}
          onChange={handleChange}
        >
          <option value="">Choisir catégorie</option>

          {categories.map((categorie) => (
            <option
              key={categorie.idCategorie}
              value={categorie.idCategorie}
            >
              {categorie.nomCategorie}
            </option>
          ))}
        </select>

        <select
          name="idDossier"
          value={form.idDossier}
          onChange={handleChange}
        >
          <option value="">Choisir dossier</option>

          {dossiers.map((dossier) => (
            <option
              key={dossier.idDossier}
              value={dossier.idDossier}
            >
              {dossier.nomDossier}
            </option>
          ))}
        </select>

        <input
          type="date"
          name="dateEmission"
          value={form.dateEmission}
          onChange={handleChange}
        />

        <input
          type="date"
          name="dateExpiration"
          value={form.dateExpiration}
          onChange={handleChange}
        />

        <input type="file" name="fichier" onChange={handleChange} />

        <button className="primary-btn">Déposer le document</button>
      </form>
    </Layout>
  );
}