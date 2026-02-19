/**
 * Ultra-Premium Performance Charts - Elite Edition
 * High-fidelity SVG rendering with neon glows and smooth transitions.
 */

document.addEventListener('DOMContentLoaded', function () {
    // Shared Palette matching premium.css
    const palette = {
        primary: '#8C00FF',
        success: '#00ff88',
        warning: '#f59e0b',
        info: '#0ea5e9',
        text: '#0f172a',
        subtext: '#94a3b8',
        glassBorder: 'rgba(255, 255, 255, 0.5)'
    };

    // 1. ELITE BAR CHART
    function createBarChart(containerId, data, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const width = container.offsetWidth;
        const height = container.offsetHeight || 300;
        const maxValue = Math.max(...data.map(d => d.value), 1);
        const colors = options.colors || [palette.primary, palette.success, palette.info, palette.warning];

        let svg = `<svg width="100%" height="100%" viewBox="0 0 ${width} ${height}" preserveAspectRatio="none">`;

        // Define Gradients
        svg += `<defs>
            ${colors.map((c, i) => `
                <linearGradient id="barGrad-${i}" x1="0" y1="0" x2="0" y2="1">
                    <stop offset="0%" stop-color="${c}" stop-opacity="1" />
                    <stop offset="100%" stop-color="${c}" stop-opacity="0.3" />
                </linearGradient>
                <filter id="glow-${i}">
                    <feGaussianBlur stdDeviation="3" result="blur" />
                    <feComposite in="SourceGraphic" in2="blur" operator="over" />
                </filter>
            `).join('')}
        </defs>`;

        const barWidth = (width / data.length) * 0.6;
        const spacing = (width / data.length) * 0.4;
        const topPadding = 60; // Increased padding for labels

        data.forEach((item, i) => {
            const barHeight = (item.value / maxValue) * (height - topPadding - 60);
            const x = i * (barWidth + spacing) + spacing / 2;
            const y = height - barHeight - 40;
            const color = colors[i % colors.length];

            svg += `
                <rect x="${x}" y="${y}" width="${barWidth}" height="${barHeight}" 
                      fill="url(#barGrad-${i % colors.length})" rx="12" filter="url(#glow-${i % colors.length})">
                    <animate attributeName="height" from="0" to="${barHeight}" dur="1s" fill="freeze" />
                    <animate attributeName="y" from="${height - 40}" to="${y}" dur="1s" fill="freeze" />
                </rect>
                <text x="${x + barWidth / 2}" y="${y - 15}" text-anchor="middle" font-size="16" font-weight="950" fill="${color}">${item.value}</text>
                <text x="${x + barWidth / 2}" y="${height - 10}" text-anchor="middle" font-size="10" font-weight="700" fill="${palette.subtext}">${item.label}</text>
            `;
        });

        svg += '</svg>';
        container.innerHTML = svg;
    }

    // 2. ELITE LINE CHART
    function createLineChart(containerId, data, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const width = container.offsetWidth;
        const height = container.offsetHeight || 300;
        const padding = 60; // Increased padding
        const maxValue = Math.max(...data.map(d => d.value), 1);
        const minValue = Math.min(...data.map(d => d.value), 0);
        const range = maxValue - minValue || 1;

        let svg = `<svg width="100%" height="100%" viewBox="0 0 ${width} ${height}">`;

        svg += `<defs>
            <linearGradient id="lineGrad" x1="0" y1="0" x2="1" y2="0">
                <stop offset="0%" stop-color="${palette.primary}" />
                <stop offset="100%" stop-color="${palette.info}" />
            </linearGradient>
            <linearGradient id="fillGrad" x1="0" y1="0" x2="0" y2="1">
                <stop offset="0%" stop-color="${palette.primary}" stop-opacity="0.2" />
                <stop offset="100%" stop-color="${palette.primary}" stop-opacity="0" />
            </linearGradient>
        </defs>`;

        const points = data.map((d, i) => {
            const x = padding + (width - 2 * padding) * i / (data.length - 1 || 1);
            const y = height - padding - ((d.value - minValue) / range) * (height - 2 * padding);
            return { x, y, value: d.value, label: d.label };
        });

        const pathData = points.map((p, i) => (i === 0 ? `M ${p.x} ${p.y}` : `L ${p.x} ${p.y}`)).join(' ');
        const areaData = pathData + ` L ${points[points.length - 1].x} ${height - padding} L ${points[0].x} ${height - padding} Z`;

        svg += `<path d="${areaData}" fill="url(#fillGrad)" />`;
        svg += `<path d="${pathData}" fill="none" stroke="url(#lineGrad)" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />`;

        points.forEach(p => {
            svg += `
                <circle cx="${p.x}" cy="${p.y}" r="6" fill="white" stroke="${palette.primary}" stroke-width="3" />
                <text x="${p.x}" y="${height - 15}" text-anchor="middle" font-size="10" font-weight="700" fill="${palette.subtext}">${p.label}</text>
            `;
        });

        svg += '</svg>';
        container.innerHTML = svg;
    }

    // 3. ELITE PIE CHART
    function createPieChart(containerId, data, options = {}) {
        const container = document.getElementById(containerId);
        if (!container) return;

        const size = Math.min(container.offsetWidth, 300);
        const radius = size / 2 - 30;
        const centerX = size / 2;
        const centerY = size / 2;
        const colors = options.colors || [palette.primary, palette.success, palette.info, palette.warning, '#f43f5e'];

        const total = data.reduce((sum, d) => sum + d.value, 0) || 1;
        let startAngle = -90;

        let svg = `<svg width="${size}" height="${size}" viewBox="0 0 ${size} ${size}">`;

        data.forEach((item, i) => {
            const angle = (item.value / total) * 360;
            const endAngle = startAngle + angle;

            const x1 = centerX + radius * Math.cos(Math.PI * startAngle / 180);
            const y1 = centerY + radius * Math.sin(Math.PI * startAngle / 180);
            const x2 = centerX + radius * Math.cos(Math.PI * endAngle / 180);
            const y2 = centerY + radius * Math.sin(Math.PI * endAngle / 180);

            const largeArcFlag = angle > 180 ? 1 : 0;
            const pathData = `M ${centerX} ${centerY} L ${x1} ${y1} A ${radius} ${radius} 0 ${largeArcFlag} 1 ${x2} ${y2} Z`;

            svg += `<path d="${pathData}" fill="${colors[i % colors.length]}" stroke="white" stroke-width="2" />`;
            startAngle = endAngle;
        });

        svg += `<circle cx="${centerX}" cy="${centerY}" r="${radius * 0.6}" fill="white" />`;
        svg += `<text x="${centerX}" y="${centerY}" text-anchor="middle" alignment-baseline="middle" font-size="24" font-weight="900" fill="${palette.text}">${total}</text>`;
        svg += '</svg>';

        // Grid Legend
        let legend = '<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 20px;">';
        data.forEach((item, i) => {
            legend += `
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div style="width: 12px; height: 12px; background: ${colors[i % colors.length]}; border-radius: 4px;"></div>
                    <span style="font-size: 11px; font-weight: 700; color: ${palette.text};">${item.label}</span>
                </div>
            `;
        });
        legend += '</div>';

        container.style.display = 'flex';
        container.style.flexDirection = 'column';
        container.style.alignItems = 'center';
        container.innerHTML = svg + legend;
    }

    // Bind to window for global access
    window.createBarChart = createBarChart;
    window.createLineChart = createLineChart;
    window.createPieChart = createPieChart;
});
