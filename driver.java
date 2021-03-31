import java.sql.*;

public class driver {
    public static void main(String[] args) {
        String username = "";
        String password = "";
        String jdbc = "jdbc:mysql://dijkstra.ug.bcc.bilkent.edu.tr/***";

        try {

            Class.forName("com.mysql.cj.jdbc.Driver");
            System.out.println("*****\n");

            Connection connection = DriverManager.getConnection(jdbc, username,password);
            System.out.println("Connected database.\n");

            Statement statement = connection.createStatement();

            statement.execute("DROP TABLE IF EXISTS owns, customer, account");
            System.out.println("Removed previous tables.\n");

            //*****CREATING*******

            statement.execute("CREATE TABLE customer " +
                    "(cid CHAR(12)," +
                    "name VARCHAR(50)," +
                    "bdate DATE," +
                    "profession VARCHAR(25)," +
                    "address VARCHAR(50)," +
                    "city VARCHAR(20)," +
                    "nationality VARCHAR(20)," +
                    "PRIMARY KEY(cid)) ENGINE=InnoDB;");


            statement.execute("CREATE TABLE account " +
                    "(aid CHAR(8), " +
                    "branch VARCHAR(20), " +
                    "balance FLOAT," +
                    "openDate DATE," +
                    "PRIMARY KEY(aid)) ENGINE=InnoDB;");


            statement.execute("CREATE TABLE owns " +
                    "(cid CHAR(12), " +
                    "aid CHAR(8)," +
                    "PRIMARY KEY(cid,aid)," +
                    "FOREIGN KEY (cid) REFERENCES customer(cid)," +
                    "FOREIGN KEY (aid) REFERENCES account(aid)) ENGINE=InnoDB;");

            //*****INSERTING*******

            statement.execute("INSERT INTO customer VALUES" +
                    "(20000001, 'Cem', '1980-10-10', 'Engineer', 'Tunali', 'Ankara', 'TC')," +
                    "(20000002, 'Asli', '1985-09-08', 'Teacher', 'Nisantasi', 'Istanbul', 'TC')," +
                    "(20000003, 'Ahmet', '1995-02-11', 'Salesman', 'Karsiyaka', 'Izmir', 'TC')," +
                    "(20000004, 'John', '1990-04-16', 'Architect', 'Kizilay', 'Ankara', 'ABD');");


            statement.execute("INSERT INTO account VALUES" +
                    "('A0000001', 'Kizilay', 2.00000, '2009-01-01')," +
                    "('A0000002', 'Bilkent', 8.00000, '2011-01-01')," +
                    "('A0000003', 'Cankaya', 4.00000, '2012-01-01')," +
                    "('A0000004', 'Sincan', 1.00000, '2012-01-01')," +
                    "('A0000005', 'Tandogan', 3.00000, '2013-01-01')," +
                    "('A0000006', 'Eryaman', 5.00000, '2015-01-01')," +
                    "('A0000007', 'Umitkoy', 6.00000, '2017-01-01');");


            statement.execute("INSERT INTO owns VALUES" +
                    "(20000001, 'A0000001')," +
                    "(20000001, 'A0000002')," +
                    "(20000001, 'A0000003')," +
                    "(20000001, 'A0000004')," +
                    "(20000002, 'A0000002')," +
                    "(20000002, 'A0000003')," +
                    "(20000002, 'A0000005')," +
                    "(20000003, 'A0000006')," +
                    "(20000003, 'A0000007')," +
                    "(20000004, 'A0000006');");

            //*****PRINT*****
            ResultSet rs = statement.executeQuery("SELECT * FROM customer");
            System.out.println("***CUSTOMER***");
            //System.out.println("cid,  name,  bdate,  profession,  address,  city,  nationality");
            while (rs.next()) {
                System.out.println(rs.getString(1) +  ",  " + rs.getString(2) + ",  " + rs.getDate(3) + ",  " + rs.getString(4) + ",  " + rs.getString(5) + ",  " + rs.getString(6) + ",  " + rs.getString(7));
            }

            rs = statement.executeQuery("SELECT * FROM account");
            System.out.println("\n***ACCOUNT***");
            //System.out.println("cid,  name,  bdate,  profession,  address,  city,  nationality");
            while (rs.next()) {
                System.out.println(rs.getString(1) +  ",  " + rs.getString(2) + ",  " + rs.getFloat(3) + ",  " + rs.getDate(4));
            }

            rs = statement.executeQuery("SELECT * FROM owns");
            System.out.println("\n***OWNS***");
            //System.out.println("cid,  name,  bdate,  profession,  address,  city,  nationality");
            while (rs.next()) {
                System.out.println(rs.getString(1) +  ",  " + rs.getString(2));
            }

        } catch (SQLException | ClassNotFoundException e) {
            System.err.println("Error Statement or Connection Failed!!!!");
            e.printStackTrace();
        }
    }
}

