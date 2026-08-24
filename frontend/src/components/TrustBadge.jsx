import { Truck, ShieldCheck, RotateCcw, Gift } from 'lucide-react';

const features = [
  { icon: Truck, title: "Worldwide Shipping", desc: "Free shipping on orders over $100" },
  { icon: ShieldCheck, title: "Secured Payment", desc: "100% secure payment methods" },
  { icon: RotateCcw, title: "30 Days Free Returns", desc: "Easy return within 30 days" },
  { icon: Gift, title: "Surprise Gift", desc: "Special gift on first order" },
];

export default function TrustBadge() {
  return (
    <section className="py-12 border-t border-b border-gray-100">
      <div className="container-custom">
        <div className="grid grid-cols-2 lg:grid-cols-4 gap-8">
          {features.map((feature, index) => (
            <div key={index} className="flex items-start gap-4">
              <feature.icon className="w-8 h-8 text-accent flex-shrink-0" />
              <div>
                <h4 className="font-semibold text-sm mb-1">{feature.title}</h4>
                <p className="text-xs text-gray-500">{feature.desc}</p>
              </div>
            </div>
          ))}
        </div>
      </div>
    </section>
  );
}