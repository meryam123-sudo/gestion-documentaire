import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Validations() {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const [documents, setDocuments] = useState([]);
  const [commentaire, setCommentaire] = useState("");

  const chargerDocuments = async () => {
    const res = await api.get("/validations/documents-soumis");
    setDocuments(res.data);
  };

  useEffect(() => {
    chargerDocuments();
  }, []);

  const decision = async (id, action) => {
    await api.post(`/validations/${id}/${action}`, {
      idUtilisateur: utilisateur.idUtilisateur,
      commentaire: commentaire || "Traitement effectué",
    });

    setCommentaire("");
    chargerDocuments();
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Validations</h1>
        <p>Documents soumis en attente de traitement</p>
      </div>

      <textarea
        className="comment-box"
        placeholder="Commentaire..."
        value={commentaire}
        onChange={(e) => setCommentaire(e.target.value)}
      />

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Référence</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Déposé par</th>
              <th>Actions</th>
            </tr>
          </thead>

          <tbody>
            {documents.map((doc) => (
              <tr key={doc.idDocument}>
                <td>{doc.reference}</td>
                <td>{doc.titre}</td>
                <td>{doc.categorie?.nomCategorie}</td>
                <td>{doc.utilisateur?.prenom} {doc.utilisateur?.nom}</td>
                <td className="actions">
                  <button onClick={() => decision(doc.idDocument, "valider")}>
                    Valider
                  </button>

                  <button onClick={() => decision(doc.idDocument, "rejeter")}>
                    Rejeter
                  </button>

                  <button onClick={() => decision(doc.idDocument, "demander-correction")}>
                    Correction
                  </button>
                </td>
              </tr>
            ))}

            {documents.length === 0 && (
              <tr>
                <td colSpan="5">Aucun document soumis</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}