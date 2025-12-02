// Fireworks celebration effect
function triggerFireworks() {
    console.log('triggerFireworks called!');
    const container = document.getElementById('fireworks-container');
    
    if (!container) {
        console.error('Fireworks container not found!');
        return;
    }
    
    container.style.display = 'block';
    
    // Create multiple firework bursts
    for (let i = 0; i < 5; i++) {
        setTimeout(() => {
            createFirework();
        }, i * 400);
    }
    
    // Hide container after 4 seconds
    setTimeout(() => {
        container.style.display = 'none';
        container.innerHTML = '';
    }, 4000);
}

function createFirework() {
    console.log('createFirework called!');
    const container = document.getElementById('fireworks-container');
    
    // Random position
    const x = Math.random() * 80 + 10; // 10-90%
    const y = Math.random() * 60 + 20; // 20-80%
    
    // Random color
    const colors = ['#da35daff', '#75b752ff', '#0000ff', '#deff0aff', '#639d98ff', '#00ffff', '#ffa500', '#ff1493'];
    const color = colors[Math.floor(Math.random() * colors.length)];
    
    console.log('Creating firework at:', x, y, 'Color:', color);
    
    // Create particles
    for (let i = 0; i < 30; i++) {
        const particle = document.createElement('div');
        particle.style.position = 'absolute';
        particle.style.left = x + '%';
        particle.style.top = y + '%';
        particle.style.width = '6px';
        particle.style.height = '6px';
        particle.style.borderRadius = '50%';
        particle.style.backgroundColor = color;
        particle.style.pointerEvents = 'none';
        
        const angle = (i * 12) * Math.PI / 180;
        const velocity = 2 + Math.random() * 2;
        const vx = Math.cos(angle) * velocity;
        const vy = Math.sin(angle) * velocity;
        
        container.appendChild(particle);
        
        // Animate particle with JavaScript
        animateParticle(particle, vx, vy);
    }
}

function animateParticle(particle, vx, vy) {
    let x = 0;
    let y = 0;
    let opacity = 1;
    let frame = 0;
    const maxFrames = 60;
    
    function animate() {
        if (frame >= maxFrames) {
            particle.remove();
            return;
        }
        
        frame++;
        x += vx;
        y += vy;
        opacity = 1 - (frame / maxFrames);
        
        particle.style.transform = `translate(${x}px, ${y}px) scale(${1 - frame / maxFrames})`;
        particle.style.opacity = opacity;
        
        requestAnimationFrame(animate);
    }
    
    animate();
}
