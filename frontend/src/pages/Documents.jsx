import { useEffect, useState } from "react";
import { useNavigate } from "react-router-dom";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Documents() {
  const [documents, setDocuments] = useState([]);
  const [recherche, setRecherche] = useState("");
  const [statut, setStatut] = useState("");
  const [reference, setReference] = useState("");

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

  const telecharger = async (id) => {
    window.open(
      `http://127.0.0.1:8000/api/documents/${id}/telecharger`,
      "_blank"
    );
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

        <select
          value={statut}
          onChange={(e) => setStatut(e.target.value)}
        >
          <option value="">Tous les statuts</option>
          <option value="brouillon">Brouillon</option>
          <option value="soumis">Soumis</option>
          <option value="validé">Validé</option>
          <option value="rejeté">Rejeté</option>
          <option value="archivé">Archivé</option>
        </select>

        <button onClick={chargerDocuments}>
          Rechercher
        </button>
      </div>

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Référence</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Statut</th>
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
                  <span className={`status-badge status-${doc.statut}`}>
  {doc.statut}
</span>
                </td>

                <td>{doc.niveauConfidentialite}</td>

                <td>
                  <div className="action-buttons">
                    <button
                      className="btn-secondary"
                      onClick={() => telecharger(doc.idDocument)}
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
  {loadingId === doc.idDocument ? "Envoi..." : "Soumettre"}
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