import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Documents() {
  const [documents, setDocuments] = useState([]);
  const [recherche, setRecherche] = useState("");
  const [statut, setStatut] = useState("");
  const [reference, setReference] = useState("");
  const [loadingId, setLoadingId] = useState(null);

  const navigate = useNavigate();

  const chargerDocuments = async () => {
    const response = await api.get("/documents", {
      params: {
        titre: recherche,
        reference: reference,
        statut: statut,
      },
    });

    setDocuments(response.data.data || response.data);
  };

  useEffect(() => {
    chargerDocuments();
  }, []);

 const telecharger = async (doc) => {
  try {
    const response = await api.get(
      `/documents/${doc.idDocument}/telecharger`,
      {
        responseType: "blob",
      }
    );

    const extensions = {
      PDF: "pdf",
      Word: "docx",
      WORD: "docx",
      DOCX: "docx",
      Excel: "xlsx",
      EXCEL: "xlsx",
      Image: "png",
      IMAGE: "png",
    };

    const extension = extensions[doc.typeDocument] || "pdf";

    const nomFichier = `${doc.reference || doc.titre}.${extension}`;

    const url = window.URL.createObjectURL(
      new Blob([response.data])
    );

    const link = document.createElement("a");
    link.href = url;
    link.download = nomFichier;

    document.body.appendChild(link);
    link.click();

    link.remove();
    window.URL.revokeObjectURL(url);
  } catch (error) {
    alert("Erreur lors du téléchargement");
  }
};

  const soumettre = async (id) => {
    try {
      setLoadingId(id);

      await api.post(`/documents/${id}/soumettre`);

      chargerDocuments();
    } catch (error) {
      alert(error.response?.data?.message);
    } finally {
      setLoadingId(null);
    }
  };

  const supprimer = async (id) => {
    if (!window.confirm("Supprimer ce document ?")) return;

    try {
      await api.delete(`/documents/${id}`);
      chargerDocuments();
    } catch (error) {
      alert(error.response?.data?.message || "Erreur lors de la suppression");
    }
  };

  return (
    <Layout>
      <div className="documents-header">
        <div>
          <h1>Documents</h1>
          <p>Gérez, recherchez et suivez vos documents</p>
        </div>

        <button
          className="primary-btn"
          onClick={() => navigate("/documents/ajouter")}
        >
          + Nouveau document
        </button>
      </div>

      <div className="filters-card">
        <input
          placeholder="Rechercher par titre..."
          value={recherche}
          onChange={(e) => setRecherche(e.target.value)}
        />

        <input
          placeholder="Référence..."
          value={reference}
          onChange={(e) => setReference(e.target.value)}
        />

        <select value={statut} onChange={(e) => setStatut(e.target.value)}>
          <option value="">Tous les statuts</option>
          <option value="brouillon">Brouillon</option>
          <option value="soumis">Soumis</option>
          <option value="validé">Validé</option>
          <option value="rejeté">Rejeté</option>
          <option value="archivé">Archivé</option>
        </select>

        <button onClick={chargerDocuments}>Rechercher</button>
      </div>

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Référence</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Statut</th>
              <th>Expiration</th>
              <th>Confidentialité</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            {documents.map((doc) => (
              <tr key={doc.idDocument}>
                <td>{doc.reference}</td>
                <td>{doc.titre}</td>
                <td>{doc.categorie?.nomCategorie}</td>

                <td>
                  <span
                    className={`status-badge status-${
                      doc.statutAffiche || doc.statut
                    }`}
                  >
                    {doc.statutAffiche || doc.statut}
                  </span>
                </td>

                <td>{doc.dateExpiration ? doc.dateExpiration : "Non définie"}</td>

                <td>{doc.niveauConfidentialite}</td>

                <td>
                  <div className="action-buttons">
                    <button
  className="btn-secondary"
  onClick={() => telecharger(doc)}
>
  Télécharger
</button>

                    {doc.statut === "brouillon" && (
                      <>
                        <button
                          className="btn-warning"
                          onClick={() =>
                            navigate(`/documents/${doc.idDocument}/modifier`)
                          }
                        >
                          Modifier
                        </button>

                        <button
                          className="btn-danger"
                          onClick={() => supprimer(doc.idDocument)}
                        >
                          Supprimer
                        </button>

                        <button
                          className="btn-success"
                          disabled={loadingId === doc.idDocument}
                          onClick={() => soumettre(doc.idDocument)}
                        >
                          {loadingId === doc.idDocument
                            ? "Envoi..."
                            : "Soumettre"}
                        </button>
                      </>
                    )}
                  </div>
                </td>
              </tr>
            ))}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}