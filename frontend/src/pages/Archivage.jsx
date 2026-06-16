import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Archivage() {
  const utilisateur = JSON.parse(localStorage.getItem("utilisateur"));
  const [documents, setDocuments] = useState([]);
  const [emplacement, setEmplacement] = useState("Archive physique - Salle A - Rayon 1");

  const chargerDocuments = async () => {
    const res = await api.get("/archives/documents-valides");
    setDocuments(res.data);
  };

  useEffect(() => {
    chargerDocuments();
  }, []);

  const archiver = async (id) => {
    await api.post(`/archives/${id}/archiver`, {
      idUtilisateur: utilisateur.idUtilisateur,
      emplacementArchive: emplacement,
      motifArchivage: "Document validé et finalisé",
    });

    chargerDocuments();
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Archivage</h1>
        <p>Documents validés prêts à être archivés</p>
      </div>

      <input
        className="comment-box"
        value={emplacement}
        onChange={(e) => setEmplacement(e.target.value)}
      />

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Référence</th>
              <th>Titre</th>
              <th>Catégorie</th>
              <th>Statut</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            {documents.map((doc) => (
              <tr key={doc.idDocument}>
                <td>{doc.reference}</td>
                <td>{doc.titre}</td>
                <td>{doc.categorie?.nomCategorie}</td>
                <td>{doc.statut}</td>
                <td>
                  <button
                    className="small-btn"
                    onClick={() => archiver(doc.idDocument)}
                  >
                    Archiver
                  </button>
                </td>
              </tr>
            ))}

            {documents.length === 0 && (
              <tr>
                <td colSpan="5">Aucun document validé à archiver</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}