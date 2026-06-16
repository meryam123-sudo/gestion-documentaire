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

    setForm({
      ...form,
      [name]: files ? files[0] : value,
    });
  };

  const deposerDocument = async (e) => {
    e.preventDefault();
    setMessage("");

    const data = new FormData();

    Object.keys(form).forEach((key) => {
      data.append(key, form[key]);
    });

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
        <input name="titre" placeholder="Titre" onChange={handleChange} />
        <input name="reference" placeholder="Référence ex: DOC-005" onChange={handleChange} />

        <textarea name="description" placeholder="Description" onChange={handleChange}></textarea>

        <select name="typeDocument" onChange={handleChange} value={form.typeDocument}>
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

        <select name="idCategorie" onChange={handleChange}>
          <option value="">Choisir catégorie</option>
          {categories.map((cat) => (
            <option key={cat.idCategorie} value={cat.idCategorie}>
              {cat.nomCategorie}
            </option>
          ))}
        </select>

        <select name="idDossier" onChange={handleChange}>
          <option value="">Choisir dossier</option>
          {dossiers.map((dos) => (
            <option key={dos.idDossier} value={dos.idDossier}>
              {dos.nomDossier}
            </option>
          ))}
        </select>

        <input type="file" name="fichier" onChange={handleChange} />

        <button className="primary-btn">Déposer le document</button>
      </form>
    </Layout>
  );
}