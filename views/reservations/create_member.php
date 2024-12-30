<style>
        .container {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f8f9fa;
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
        }
        h2 {
            color: #333;
        }
        .user-info, .book-list, .appointment-card {
            margin-bottom: 20px;
        }
        .border {
            border: 1px solid #ccc;
            border-radius: 5px;
            padding: 15px;
        }
        .btn {
            display: inline-block;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            text-align: center;
        }
        .btn:hover {
            background-color: #0056b3;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        table th {
            background-color: #f2f2f2;
            color: #333;
        }
    </style>
    <div class="container">
        <div class="header">
            <h2>Đặt Hẹn Sách Thư Viện</h2>
        </div>

        <!-- Thông tin người dùng -->
        <div class="user-info border">
            <h4>Thông Tin Người Dùng</h4>
            <p><strong>Họ và Tên:</strong> Nguyễn Văn A</p>
            <p><strong>Mã Sinh Viên:</strong> 123456789</p>
            <p><strong>Trường:</strong> Đại học ABC</p>
        </div>

        <!-- Danh sách sách -->
        <div class="book-list border">
            <h4>Danh Sách Sách</h4>
            <table>
                <thead>
                    <tr>
                        <th>Chọn</th>
                        <th>Mã Sách</th>
                        <th>Tên Sách</th>
                        <th>Tác Giả</th>
                        <th>Số Lượng</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" class="book-checkbox" data-id="B001" data-title="Lập trình C++" data-author="Nguyễn Văn B"></td>
                        <td>B001</td>
                        <td>Lập trình C++</td>
                        <td>Nguyễn Văn B</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td><input type="checkbox" class="book-checkbox" data-id="B002" data-title="Học Python" data-author="Trần Văn C"></td>
                        <td>B002</td>
                        <td>Học Python</td>
                        <td>Trần Văn C</td>
                        <td>3</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- Phiếu hẹn -->
        <div class="appointment-card border">
            <h4>Phiếu Hẹn Sách</h4>
            <table id="appointmentTable">
                <thead>
                    <tr>
                        <th>Mã Sách</th>
                        <th>Tên Sách</th>
                        <th>Tác Giả</th>
                        <th>Hành Động</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
        </div>

        <!-- Nút gửi phiếu hẹn -->
        <div style="text-align: right;">
            <button class="btn" id="submitAppointment">Gửi Phiếu Hẹn</button>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            const bookCheckboxes = document.querySelectorAll(".book-checkbox");
            const appointmentTable = document.querySelector("#appointmentTable tbody");

            // Hàm thêm sách vào phiếu hẹn
            bookCheckboxes.forEach(checkbox => {
                checkbox.addEventListener("change", function () {
                    if (this.checked) {
                        const row = document.createElement("tr");
                        row.innerHTML = `
                            <td>${this.dataset.id}</td>
                            <td>${this.dataset.title}</td>
                            <td>${this.dataset.author}</td>
                            <td><button class="btn remove-btn">Xóa</button></td>
                        `;
                        appointmentTable.appendChild(row);

                        // Xử lý nút xóa sách
                        row.querySelector(".remove-btn").addEventListener("click", function () {
                            row.remove();
                            checkbox.checked = false;
                        });
                    } else {
                        const rows = Array.from(appointmentTable.children);
                        rows.forEach(row => {
                            if (row.children[0].textContent === this.dataset.id) {
                                row.remove();
                            }
                        });
                    }
                });
            });

            // Xử lý khi gửi phiếu hẹn
            document.querySelector("#submitAppointment").addEventListener("click", function () {
                const rows = Array.from(appointmentTable.children);
                if (rows.length === 0) {
                    alert("Vui lòng chọn ít nhất một sách để đặt hẹn!");
                    return;
                }

                let appointmentData = rows.map(row => {
                    return {
                        id: row.children[0].textContent,
                        title: row.children[1].textContent,
                        author: row.children[2].textContent,
                    };
                });

                console.log("Phiếu hẹn được gửi:", appointmentData);
                alert("Phiếu hẹn sách đã được gửi thành công!");

                // Xóa phiếu hẹn sau khi gửi
                appointmentTable.innerHTML = "";
                bookCheckboxes.forEach(cb => cb.checked = false);
            });
        });
    </script>
</body>