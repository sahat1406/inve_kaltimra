use App\Models\Role;
use App\Models\User;
use App\Models\Kategori;
use App\Models\Lokasi;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BuatQrCodeBarangTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dapat_menyimpan_barang_dan_qrcode_dibuat()
    {
        Storage::fake('public');

        // Tambahkan role admin secara manual ke DB test
        $adminRole = Role::create(['id' => 1, 'role' => 'admin']);

        $kategori = Kategori::factory()->create();
        $lokasi   = Lokasi::factory()->create();

        // Buat user admin dengan role_id yang sesuai
        $admin = User::factory()->create([
            'role_id' => $adminRole->id,
        ]);
        $this->actingAs($admin);

        $response = $this->post('/barang', [
            'nm_barang'     => 'Laptop',
            'deskripsi'     => 'Laptop untuk kerja',
            'kategori_id'   => $kategori->id,
            'lokasi_id'     => $lokasi->id,
            'gambar'        => UploadedFile::fake()->image('laptop.png'),
        ]);

        $response->assertRedirect('/barang');

        $this->assertDatabaseHas('barangs', [
            'nm_barang' => 'Laptop',
        ]);

        $barang = \App\Models\Barang::latest()->first();
        $qrPath = 'qrcode/' . $barang->kd_barang . '.png';
        Storage::disk('public')->assertExists($qrPath);
    }
}
