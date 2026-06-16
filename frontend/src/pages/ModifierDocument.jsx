import { useEffect, useState } from "react";
import { useNavigate, useParams } from "react-router-dom";
import Layout from "../components/Layout";
import api from "../services/api";

export default function ModifierDocument() {
  const { id } = useParams();
  const navigate = useNavigate();

  const [categories, setCategories] = useState([]);
  const [dossiers, setDossiers] = useState([]);
  const [message, setMessage] = useState("");

  const [form, setForm] = useState({
    titre: "",
    reference: "",
    description: "",
    typeDocument: "",
    niveauConfidentialite: "",
    idCategorie: "",
    idDossier: "",
  });

  useEffect(() => {
    api.get(`/documents/${id}`).then((res) => {
      const doc = res.data;

      setForm({
        titre: doc.titre || "",
        reference: doc.reference || "",
        description: doc.description || "",
        typeDocument: doc.typeDocument || "",
        niveauConfidentialite: doc.niveauConfidentialite || "",
        idCategorie: doc.idCategorie || "",
        idDossier: doc.idDossier || "",
      });
    });

    api.get("/categories").then((res) => setCategories(res.data.data || res.data));
    api.get("/dossiers").then((res) => setDossiers(res.data.data || res.data));
  }, [id]);

  const handleChange = (e) => {
    setForm({
      ...form,
      [e.target.name]: e.target.value,
    });
  };

  const modifier = async (e) => {
    e.preventDefault();

    try {
      await api.put(`/documents/${id}`, form);
      navigate("/documents");
    } catch (error) {
      setMessage(error.response?.data?.message || "Erreur lors de la modification");
    }
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Modifier document</h1>
        <p>Modification autorisée seulement si le document est en brouillon</p>
      </div>

      {message && <div className="error-box">{message}</div>}

      <form className="form-card" onSubmit={modifier}>
        <input name="titre" value={form.titre} onChange={handleChange} />
        <input name="reference" value={form.reference} onChange={handleChange} />

        <textarea
          name="description"
          value={form.description}
          onChange={handleChange}
        />

        <select
          name="typeDocument"
          value={form.typeDocument}
          onChange={handleChange}
        >
          <option value="PDF">PDF</option>
          <option value="DOC">DOC</option>
          <option value="DOCX">DOCX</option>
          <option value="IMAGE">Image</option>
        </select>

        <select
          name="niveauConfidentialite"
          value={form.niveauConfidentialite}
          onChange={handleChange}
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
          {categories.map((cat) => (
            <option key={cat.idCategorie} value={cat.idCategorie}>
              {cat.nomCategorie}
            </option>
          ))}
        </select>

        <select
          name="idDossier"
          value={form.idDossier}
          onChange={handleChange}
        >
          {dossiers.map((dos) => (
            <option key={dos.idDossier} value={dos.idDossier}>
              {dos.nomDossier}
            </option>
          ))}
        </select>

        <button className="primary-btn">
          Enregistrer les modifications
        </button>
      </form>
    </Layout>
  );
}