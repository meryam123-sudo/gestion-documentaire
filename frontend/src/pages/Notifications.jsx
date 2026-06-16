import { useEffect, useState } from "react";
import Layout from "../components/Layout";
import api from "../services/api";

export default function Notifications() {
  const [notifications, setNotifications] = useState([]);

  const chargerNotifications = async () => {
    const res = await api.get("/notifications");
    setNotifications(res.data);
  };

  useEffect(() => {
    chargerNotifications();
  }, []);

  const marquerLue = async (id) => {
    await api.put(`/notifications/${id}/lue`);
    chargerNotifications();
  };

  return (
    <Layout>
      <div className="page-header">
        <h1>Notifications</h1>
        <p>Suivi des messages automatiques</p>
      </div>

      <div className="table-card">
        <table>
          <thead>
            <tr>
              <th>Type</th>
              <th>Message</th>
              <th>Document</th>
              <th>État</th>
              <th>Action</th>
            </tr>
          </thead>

          <tbody>
            {notifications.map((notif) => (
              <tr key={notif.idNotification}>
                <td>{notif.typeNotification}</td>
                <td>{notif.message}</td>
                <td>{notif.document?.titre}</td>
                <td>{notif.lue ? "Lue" : "Non lue"}</td>
                <td>
                  {!notif.lue && (
                    <button
                      className="small-btn"
                      onClick={() => marquerLue(notif.idNotification)}
                    >
                      Marquer comme lue
                    </button>
                  )}
                </td>
              </tr>
            ))}

            {notifications.length === 0 && (
              <tr>
                <td colSpan="5">Aucune notification</td>
              </tr>
            )}
          </tbody>
        </table>
      </div>
    </Layout>
  );
}