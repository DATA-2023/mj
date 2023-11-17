<html>
<head>
    <title>서울시 공공자전거 이용정보 보고서</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f2f2f2;
        }
        header {
            background-color: #0078d4;
            color: #fff;
            text-align: center;
            padding: 20px;
        }
        h1 {
            font-size: 24px;
        }
        .container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }
        h2 {
            font-size: 20px;
            margin-top: 20px;
        }
        p {
            font-size: 16px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #0078d4;
            color: #fff;
        }
        /* 네비게이션바 스타일 */
        nav {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 10px;
        }
        nav a {
            color: #fff;
            text-decoration: none;
            margin: 10px;
        }
    </style>
</head>
<body>
    <header>
        <h1>서울시 공공자전거 이용정보 보고서</h1>
    </header>
    <nav>
        <!-- 자기 파트 웹페이지 만들어서 제목 추하 html파일 연결하기-->
        <a href="#summary">이용 현황 요약</a>
        <a href="#monthlyChart">월별 이용 건수 그래프</a>
        <a href="#detailData">상세 데이터</a>
    </nav>
    <div class="container">
        <h2>회원별 운동량과 탄소절감량</h2>
        <p>서울시 공공자전거를 이용하는 회원들의 운동량, 탄소절감량에 대한 정보입니다.<br>성별, 나이대별로 확인하실 수 있습니다.</p>
        
        

        <?php
           // 데이터베이스 연결 설정
            $host = "localhost";
            $user = "pma";
            $pw = "kcQ!L51bLM6zZfE(";
            $dbName = "bigdata_db";

            $conn = new mysqli($host, $user, $pw, $dbName);

            // 오류 확인
            if ($conn->connect_error) {
               die("Connection failed: " . $conn->connect_error);
            }

            // 사용자가 클릭한 성별 및 연령대
            $selected_gender = isset($_GET['gender']) ? $_GET['gender'] : '';
            $selected_age_group = isset($_GET['age_group']) ? $_GET['age_group'] : '';

            // SQL 쿼리 작성
            $query = "SELECT
            u.gender,
            u.age_group,
            SUM(wu.dur_time) AS total_duration,
            SUM(wu.exercise) AS total_exercise
          FROM
            user u
          JOIN
            usage_per_user upu ON upu.user_id = u.user_id
          JOIN
            workout_usage wu ON upu.usage_id = wu.usage_id
          WHERE
            (u.gender = '$selected_gender' OR '$selected_gender' = '')
            AND (u.age_group = '$selected_age_group' OR '$selected_age_group' = '')
          GROUP BY
            u.gender, u.age_group";

            // 쿼리 실행
            $result = $conn->query($query);

            // 결과 출력
            echo "<form action='' method='GET'>";
            echo "<label>성별 :  </label>";
            echo "<select name='gender'>";
            echo "<option value=''>전체</option>";
            echo "<option value='F' " . ($selected_gender == 'F' ? 'selected' : '') . ">여성</option>";
            echo "<option value='M' " . ($selected_gender == 'M' ? 'selected' : '') . ">남성</option>";
            echo "</select>";

            echo "<label>나이대 :  </label>";
            echo "<select name='age_group'>";
            echo "<option value=''>전체</option>";
            echo "<option value='10대' " . ($selected_age_group == '10대' ? 'selected' : '') . ">10대</option>";
            echo "<option value='20대' " . ($selected_age_group == '20대' ? 'selected' : '') . ">20대</option>";
            echo "<option value='30대' " . ($selected_age_group == '30대' ? 'selected' : '') . ">30대</option>";
            echo "<option value='40대' " . ($selected_age_group == '40대' ? 'selected' : '') . ">40대</option>";
            echo "<option value='50대' " . ($selected_age_group == '50대' ? 'selected' : '') . ">50대</option>";
            echo "<option value='60대' " . ($selected_age_group == '60대' ? 'selected' : '') . ">60대</option>";
            echo "<option value='70대이상' " . ($selected_age_group == '70대이상' ? 'selected' : '') . ">70대이상</option>";
            echo "</select>";

            echo "<input type='submit' value='조회'>";
            echo "</form>";

            echo "<table border='1'>";
            echo "<tr><th>성별</th><th>나이대</th><th>총 탄소절감량</th><th>총 운동량</th></tr>";

            while ($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row['gender'] . "</td>";
                echo "<td>" . $row['age_group'] . "</td>";
                echo "<td>" . $row['total_duration'] . "</td>";
                echo "<td>" . $row['total_exercise'] . "</td>";
                echo "</tr>";
            }

            echo "</table>";

            // 연결 종료
            $conn->close();
            ?>  
    </div>

    
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        

    </script>
</body>
</html>