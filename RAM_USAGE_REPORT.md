# UTB HR Central - RAM Usage Report

**Date**: November 2025  
**Repository**: https://github.com/MatinShmli/UTB-HR-CENTRAL-PROJECT-HANDOVER.git

---

## Current Configuration

### PHP Memory Settings
- **PHP Memory Limit**: **128 MB** (default)
- **Application Memory Limit**: **256 MB** (configured in `.htaccess` and `.user.ini`)
- **Upload Max Filesize**: 10 MB
- **Post Max Size**: 10 MB

### Current Running Processes (Development)
- **HerdHelper**: ~5 MB
- **MySQL**: ~6 MB (2 processes)
- **Total Active**: ~11 MB (when idle)

---

## RAM Usage Breakdown

### Per Request (PHP Application)

| Operation Type | Typical RAM Usage | Peak RAM Usage |
|----------------|-------------------|----------------|
| **Simple Page Load** | 10-30 MB | 50 MB |
| **Complex Page (with queries)** | 20-50 MB | 80 MB |
| **PDF Generation** | 50-100 MB | 150 MB |
| **File Uploads** | 30-80 MB | 120 MB |
| **Data Export** | 40-100 MB | 150 MB |
| **Bulk Operations** | 60-150 MB | 256 MB |

**Note**: The application is configured with a 256 MB memory limit per request, which is sufficient for most operations.

---

## Background Services RAM Usage

### Development Environment

| Service | Typical Usage | Peak Usage |
|---------|---------------|------------|
| **MySQL** | 100-200 MB | 300 MB |
| **PHP-FPM** (if used) | 50-100 MB | 200 MB |
| **Web Server** (Apache/Nginx) | 20-50 MB | 100 MB |
| **Node.js** (build process) | 50-100 MB | 200 MB |
| **Laravel Queue Worker** | 30-80 MB | 150 MB |

**Total Development**: **250-530 MB** (typical), **950 MB** (peak)

### Production Environment

| Service | Small Deployment | Medium Deployment | Large Deployment |
|---------|------------------|-------------------|------------------|
| **MySQL** | 100-200 MB | 200-400 MB | 400-800 MB |
| **PHP-FPM Pool** (5-10 workers) | 200-500 MB | 500-1000 MB | 1000-2000 MB |
| **Web Server** (Nginx/Apache) | 50-100 MB | 100-200 MB | 200-400 MB |
| **Cache (Redis/Memcached)** | 50-100 MB | 100-200 MB | 200-500 MB |
| **Queue Workers** | 50-100 MB | 100-200 MB | 200-400 MB |

**Total Production**:
- **Small**: **450-1000 MB** (~512 MB - 1 GB)
- **Medium**: **1000-2000 MB** (~1-2 GB)
- **Large**: **2000-4100 MB** (~2-4 GB)

---

## System Requirements

### Minimum Requirements

#### Development
- **RAM**: **2 GB**
- **PHP Memory Limit**: 128 MB
- **MySQL**: 100 MB allocated
- **OS**: Windows 10/11, macOS, or Linux

#### Production
- **RAM**: **512 MB** (minimum, not recommended)
- **PHP Memory Limit**: 128-256 MB
- **MySQL**: 200 MB allocated
- **OS**: Linux (Ubuntu 20.04+ recommended)

### Recommended Requirements

#### Development
- **RAM**: **4 GB** or more
- **PHP Memory Limit**: 256 MB
- **MySQL**: 200-500 MB allocated
- **OS**: Windows 10/11, macOS, or Linux
- **CPU**: 2+ cores

#### Production
- **RAM**: **1-2 GB** (small to medium deployments)
- **RAM**: **2-4 GB** (large deployments, 100+ concurrent users)
- **PHP Memory Limit**: 256 MB
- **MySQL**: 500 MB - 1 GB allocated
- **OS**: Linux (Ubuntu 20.04+ or CentOS 7+)
- **CPU**: 2+ cores

---

## Concurrent Users Impact

### RAM Usage by Concurrent Users

| Concurrent Users | PHP-FPM Workers | RAM Usage | Total System RAM |
|------------------|-----------------|-----------|------------------|
| **1-10** | 2-5 workers | 100-250 MB | 512 MB - 1 GB |
| **10-50** | 5-10 workers | 250-500 MB | 1-2 GB |
| **50-100** | 10-20 workers | 500-1000 MB | 2-4 GB |
| **100-200** | 20-40 workers | 1000-2000 MB | 4-8 GB |
| **200+** | 40+ workers | 2000+ MB | 8+ GB |

**Formula**: 
- Each PHP-FPM worker: ~50-100 MB
- MySQL: 100-500 MB (base) + 10-20 MB per 10 concurrent connections
- Web Server: 50-100 MB (base)

---

## Memory Optimization Tips

### 1. PHP Configuration
```ini
; Recommended settings in php.ini
memory_limit = 256M
max_execution_time = 300
max_input_time = 300
```

### 2. PHP-FPM Pool Configuration
```ini
; Recommended for small to medium deployments
pm = dynamic
pm.max_children = 10
pm.start_servers = 3
pm.min_spare_servers = 2
pm.max_spare_servers = 5
pm.max_requests = 500
```

### 3. MySQL Configuration
```ini
; Recommended for small to medium deployments
innodb_buffer_pool_size = 256M
max_connections = 50
query_cache_size = 32M
```

### 4. Laravel Optimization
- Use **caching** for frequently accessed data
- Use **queue workers** for heavy operations
- Enable **OPcache** for PHP
- Use **Redis** or **Memcached** for session storage
- Optimize database queries (use indexes, eager loading)

### 5. Production Deployment
- Use **Nginx** instead of Apache (lower memory footprint)
- Enable **OPcache** for PHP
- Use **Redis** for caching and sessions
- Implement **CDN** for static assets
- Use **database connection pooling**

---

## Monitoring RAM Usage

### Development
```bash
# Check PHP memory usage
php -r "echo ini_get('memory_limit');"

# Monitor processes
# Windows PowerShell
Get-Process | Where-Object {$_.ProcessName -match 'php|mysql'} | Select-Object ProcessName, @{Name='Memory(MB)';Expression={[math]::Round($_.WorkingSet64/1MB,2)}}

# Linux/Mac
ps aux | grep -E 'php|mysql' | awk '{print $2, $4, $11}'
```

### Production
- Use **Laravel Telescope** (development only)
- Use **Laravel Pulse** (monitoring)
- Use **New Relic** or **Datadog** (APM)
- Use **htop** or **top** (system monitoring)
- Monitor MySQL with `SHOW PROCESSLIST;`

---

## Typical Scenarios

### Scenario 1: Small Organization (10-50 users)
- **Concurrent Users**: 5-15
- **PHP-FPM Workers**: 5-10
- **MySQL Connections**: 10-20
- **Total RAM**: **1-2 GB**
- **Peak RAM**: **2 GB**

### Scenario 2: Medium Organization (50-200 users)
- **Concurrent Users**: 15-50
- **PHP-FPM Workers**: 10-20
- **MySQL Connections**: 20-50
- **Total RAM**: **2-4 GB**
- **Peak RAM**: **4 GB**

### Scenario 3: Large Organization (200+ users)
- **Concurrent Users**: 50-150
- **PHP-FPM Workers**: 20-40
- **MySQL Connections**: 50-100
- **Total RAM**: **4-8 GB**
- **Peak RAM**: **8 GB**

---

## Database RAM Usage

### MySQL Memory Allocation

| Component | Small DB | Medium DB | Large DB |
|-----------|----------|-----------|----------|
| **InnoDB Buffer Pool** | 128 MB | 256-512 MB | 1-2 GB |
| **Query Cache** | 16 MB | 32-64 MB | 128 MB |
| **Connection Pool** | 50 MB | 100 MB | 200 MB |
| **Temporary Tables** | 32 MB | 64 MB | 128 MB |
| **Total** | **226 MB** | **452-756 MB** | **1.5-2.5 GB** |

**Note**: Database size doesn't directly correlate with RAM usage. RAM is used for:
- Caching frequently accessed data
- Query execution buffers
- Connection management
- Temporary tables

---

## Summary

### Current Project RAM Usage

| Environment | Typical Usage | Peak Usage | Recommended RAM |
|-------------|---------------|------------|-----------------|
| **Development** | 250-500 MB | 1 GB | 4 GB |
| **Production (Small)** | 512 MB - 1 GB | 2 GB | 1-2 GB |
| **Production (Medium)** | 1-2 GB | 4 GB | 2-4 GB |
| **Production (Large)** | 2-4 GB | 8 GB | 4-8 GB |

### Key Points

1. **Per Request**: 10-100 MB (depending on operation)
2. **PHP Memory Limit**: 256 MB (configured)
3. **MySQL**: 100-500 MB (typical)
4. **Total System**: 512 MB - 4 GB (depending on deployment size)
5. **Concurrent Users**: Each user adds ~10-50 MB to PHP-FPM pool

### Recommendations

- **Development**: 4 GB RAM minimum
- **Production (Small)**: 1-2 GB RAM
- **Production (Medium)**: 2-4 GB RAM
- **Production (Large)**: 4-8 GB RAM

---

**Report Generated**: November 2025  
**Project**: UTB HR Central  
**Location**: C:\Users\ampma\Herd\internutb

