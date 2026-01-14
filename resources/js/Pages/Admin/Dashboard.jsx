import React from 'react';
import AdminLayout from '@/Layouts/AdminLayout';
import { Wifi, Users, MessageSquareWarning } from 'lucide-react';

export default function Dashboard({ stats, recentActivity }) {
  // stats dan recentActivity dikirim dari Laravel Controller (Inertia Props)

  const cards = [
    { title: 'Total Pendapatan', value: stats.revenue, icon: <Wifi className="text-blue-500" />, color: 'bg-blue-50' },
    { title: 'Pelanggan Aktif', value: stats.activeUsers, icon: <Users className="text-green-500" />, color: 'bg-green-50' },
    { title: 'Keluhan Pending', value: stats.pendingComplaints, icon: <MessageSquareWarning className="text-orange-500" />, color: 'bg-orange-50' },
  ];

  return (
    <AdminLayout title="Dashboard">
      <div className="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        {cards.map((card, idx) => (
          <div key={idx} className="bg-white p-6 rounded-xl shadow-sm border border-gray-100 flex items-center space-x-4">
            <div className={`p-3 rounded-lg ${card.color}`}>{card.icon}</div>
            <div>
              <p className="text-sm text-gray-500">{card.title}</p>
              <h3 className="text-2xl font-bold text-gray-800">{card.value}</h3>
            </div>
          </div>
        ))}
      </div>

      <div className="bg-white p-6 rounded-xl shadow-sm border border-gray-100">
        <h3 className="font-bold text-gray-700 mb-4">Aktivitas Terkini</h3>
        {/* Render recentActivity list here */}
      </div>
    </AdminLayout>
  );
}
